<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Prestamo;
use App\Models\Cuota;
use App\Services\Pagos\AsignarPagos;
use App\Services\Prestamos\ActualizarEstadoPrestamo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PagosController extends Controller
{
    public function __construct(
        private ActualizarEstadoPrestamo $actualizarEstadoPrestamo,
        private AsignarPagos $asignarPagos
    ) {}

    public function index()
    {
        $pagos = Pago::with([
                'prestamo.cliente',
                'asignaciones.cuota',
                'recibidoPor',
                'anuladoPor',
            ])
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.pagos.index', compact('pagos'));
    }

    public function create()
    {
        $prestamos = Prestamo::with('cliente')
            ->whereIn('estado', ['activo', 'en_mora'])
            ->orderByDesc('id')
            ->get();

        return view('admin.pagos.create', compact('prestamos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'prestamo_id' => ['required', 'exists:prestamos,id'],
            'cuota_id'    => ['nullable', 'exists:cuotas,id'],
            'monto'       => ['required', 'numeric', 'min:1'],
            'metodo'      => ['required', 'string'],
            'referencia'  => ['nullable', 'string'],
            'notas'       => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($data) {

            $prestamo = Prestamo::lockForUpdate()->findOrFail($data['prestamo_id']);

            if (!in_array($prestamo->estado, ['activo', 'en_mora'], true)) {
                abort(422, 'Solo se pueden registrar pagos para préstamos activos o en mora.');
            }

            //  Regla de integridad: no permitir pagos si no hay cuotas generadas
            if (!$prestamo->cuotas()->exists()) {
                abort(422, 'Este préstamo no tiene cuotas generadas. Genera/valida las cuotas antes de registrar pagos.');
            }

            // 1) Crear el pago (evento)
            $pago = Pago::create([
                'prestamo_id'  => $prestamo->id,
                'pagado_en'    => now(),
                'metodo'       => $data['metodo'],
                'referencia'   => $data['referencia'] ?? null,
                'recibido_por' => Auth::id(),
                'monto'        => $data['monto'],
                'estado'       => 'registrado',
                'notas'        => $data['notas'] ?? null,
            ]);

            // 2) Asignar el pago usando el Service (sin duplicar lógica aquí)
            $montoRestante = $this->asignarPagos->asignarEnOrden(
                pago: $pago,
                cuotaForzadaId: !empty($data['cuota_id']) ? (int)$data['cuota_id'] : null
            );

            // 3) Caja IN (blindado contra concurrencia: lock a la última fila)
            $ultimaCaja = DB::table('caja')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            $saldoAnterior = $ultimaCaja->saldo_despues ?? 0;

            DB::table('caja')->insert([
                'fecha'           => now(),
                'monto'           => $data['monto'],
                'direccion'       => 'IN',
                'concepto'        => 'Pago préstamo #' . $prestamo->id,
                'tipo_referencia' => 'pago',
                'id_referencia'   => $pago->id,
                'creado_por'      => Auth::id(),
                'saldo_despues'   => $saldoAnterior + $data['monto'],
                'estado'          => 'normal',
                'created_at'      => now(),
            ]);

            // Opcional: si quieres prohibir “saldo a favor” por ahora:
            if ($montoRestante > 0) {
                abort(422, 'El pago excede el saldo total pendiente del préstamo.');
            }

            // 4) Recalcular estado del préstamo (activo/en_mora/finalizado) según cuotas
            $prestamo->refresh();
            $this->actualizarEstadoPrestamo->ejecutar($prestamo);
        });

        return redirect()
            ->route('admin.pagos.index')
            ->with('success', 'Pago registrado correctamente');
    }

    public function show(Pago $pago)
    {
        $pago->load([
            'prestamo.cliente',
            'asignaciones.cuota',
            'recibidoPor',
            'anuladoPor',
        ]);

        return view('admin.pagos.show', compact('pago'));
    }

    public function edit(Pago $pago)
    {
        $pago->load(['prestamo.cliente', 'asignaciones.cuota']);
        return view('admin.pagos.edit', compact('pago'));
    }

    public function update(Request $request, Pago $pago)
    {
        if ($pago->estado === 'anulado') {
            return back()->with('error', 'No puedes editar un pago anulado.');
        }

        $data = $request->validate([
            'metodo'     => ['required', 'string'],
            'referencia' => ['nullable', 'string'],
            'notas'      => ['nullable', 'string'],
        ]);

        $pago->update($data);

        return redirect()
            ->route('admin.pagos.show', $pago)
            ->with('success', 'Pago actualizado.');
    }

    public function anular(Pago $pago)
    {
        if ($pago->estado === 'anulado') {
            return back()->with('error', 'El pago ya está anulado');
        }

        DB::transaction(function () use ($pago) {

            // Lock del préstamo (para evitar carreras con otro pago/anulación)
            $prestamo = Prestamo::lockForUpdate()->findOrFail($pago->prestamo_id);

            $pago->load('asignaciones');

            // 1) Revertir cuotas según asignaciones
            foreach ($pago->asignaciones as $asig) {
                $cuota = Cuota::lockForUpdate()->findOrFail($asig->cuota_id);

                $cuota->update([
                    'total_pagado' => (float) $cuota->total_pagado - (float) $asig->capital_pagado,
                    'saldo_cuota'  => (float) $cuota->saldo_cuota + (float) $asig->capital_pagado,
                ]);

                // Recalcular estado según saldo
                $cuota->refresh();
                $estado = ((float) $cuota->saldo_cuota <= 0) ? 'pagada'
                    : (((float) $cuota->total_pagado <= 0) ? 'pendiente' : 'parcial');

                $cuota->update([
                    'estado'    => $estado,
                    'pagado_en' => $estado === 'pagada' ? ($cuota->pagado_en ?? now()) : null,
                ]);
            }

            // 2) Caja OUT (lock a la última fila)
            $ultimaCaja = DB::table('caja')
                ->orderByDesc('id')
                ->lockForUpdate()
                ->first();

            $saldoAnterior = $ultimaCaja->saldo_despues ?? 0;

            DB::table('caja')->insert([
                'fecha'           => now(),
                'monto'           => $pago->monto,
                'direccion'       => 'OUT',
                'concepto'        => 'Anulación pago #' . $pago->id,
                'tipo_referencia' => 'pago',
                'id_referencia'   => $pago->id,
                'creado_por'      => Auth::id(),
                'saldo_despues'   => $saldoAnterior - $pago->monto,
                'estado'          => 'normal',
                'created_at'      => now(),
            ]);

            // 3) Marcar pago anulado
            $pago->update([
                'estado'      => 'anulado',
                'anulado_en'  => now(),
                'anulado_por' => Auth::id(),
            ]);

            // 4) Recalcular estado del préstamo según cuotas
            $prestamo->refresh();
            $this->actualizarEstadoPrestamo->ejecutar($prestamo);
        });

        return back()->with('success', 'Pago anulado correctamente');
    }
}

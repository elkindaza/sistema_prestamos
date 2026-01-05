<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Prestamo;
use App\Models\Cuota;
use App\Models\AsignacionPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PagosController extends Controller
{
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
            ->whereIn('estado', ['activo','en_mora'])
            ->orderByDesc('id')
            ->get();

        return view('admin.pagos.create', compact('prestamos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'prestamo_id' => ['required', 'exists:prestamos,id'],
            // cuota_id ya NO es requerida. La dejamos opcional por si luego quieres “forzar cuota”.
            'cuota_id'    => ['nullable', 'exists:cuotas,id'],
            'monto'       => ['required', 'numeric', 'min:1'],
            'metodo'      => ['required', 'string'],
            'referencia'  => ['nullable', 'string'],
            'notas'       => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($data) {

            $prestamo = Prestamo::lockForUpdate()->findOrFail($data['prestamo_id']);

            if (!in_array($prestamo->estado, ['activo', 'en_mora'])) {
                abort(422, 'Solo se pueden registrar pagos para préstamos activos o en mora.');
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

            // 2) Asignar el pago: en orden de vencimiento (y número), o forzado si viene cuota_id
            $montoRestante = (float)$data['monto'];

            if (!empty($data['cuota_id'])) {
                // ✅ Forzar a una cuota específica (opcional)
                $cuota = Cuota::lockForUpdate()->findOrFail($data['cuota_id']);

                if ((int)$cuota->prestamo_id !== (int)$prestamo->id) {
                    abort(422, 'La cuota seleccionada no pertenece a ese préstamo.');
                }
                if ($cuota->estado === 'pagada' || (float)$cuota->saldo_cuota <= 0) {
                    abort(422, 'La cuota seleccionada ya está pagada.');
                }

                // Aun así permitimos que el usuario pague más (multi-cuota) solo si tú quieres.
                // Si quieres limitarlo, aquí podrías volver a validar.
                $montoRestante = $this->aplicarMontoACuota($pago->id, $cuota, $montoRestante);
            }

            // ✅ Asignación automática a las demás cuotas (si quedó saldo)
            if ($montoRestante > 0) {
                $cuotas = Cuota::where('prestamo_id', $prestamo->id)
                    ->whereIn('estado', ['pendiente', 'parcial', 'vencida'])
                    ->where('saldo_cuota', '>', 0)
                    ->orderBy('fecha_vencimiento')
                    ->orderBy('numero')
                    ->lockForUpdate()
                    ->get();

                foreach ($cuotas as $cuota) {
                    if ($montoRestante <= 0) break;
                    $montoRestante = $this->aplicarMontoACuota($pago->id, $cuota, $montoRestante);
                }
            }

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
            // if ($montoRestante > 0) abort(422, 'El pago excede el saldo total pendiente del préstamo.');
            // Si NO lo prohíbes, te queda un "saldo a favor" implícito (pero aún no lo guardas en DB).
        });

        return redirect()
            ->route('admin.pagos.index')
            ->with('success', 'Pago registrado correctamente');
    }

    /**
     * Aplica un monto a una cuota y crea la asignación.
     * Por ahora todo va a capital_pagado (intereses/mora = 0).
     * Retorna el monto restante que NO se alcanzó a aplicar.
     */
    private function aplicarMontoACuota(int $pagoId, Cuota $cuota, float $montoDisponible): float
    {
        $saldo = (float)$cuota->saldo_cuota;
        if ($saldo <= 0 || $montoDisponible <= 0) {
            return $montoDisponible;
        }

        $aplicar = min($saldo, $montoDisponible);

        AsignacionPago::create([
            'pago_id'          => $pagoId,
            'cuota_id'         => $cuota->id,
            'capital_pagado'   => $aplicar,
            'intereses_pagado' => 0,
            'mora_pagada'      => 0,
            'asignado_en'      => now(),
        ]);

        $nuevoSaldo = $saldo - $aplicar;
        $nuevoTotalPagado = (float)$cuota->total_pagado + $aplicar;

        $cuota->update([
            'total_pagado' => $nuevoTotalPagado,
            'saldo_cuota'  => $nuevoSaldo,
            'estado'       => $nuevoSaldo <= 0 ? 'pagada' : 'parcial',
            'pagado_en'    => $nuevoSaldo <= 0 ? now() : null,
        ]);

        return $montoDisponible - $aplicar;
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
        $pago->load(['prestamo.cliente','asignaciones.cuota']);
        return view('admin.pagos.edit', compact('pago'));
    }

    public function update(Request $request, Pago $pago)
    {
        if ($pago->estado === 'anulado') {
            return back()->with('error', 'No puedes editar un pago anulado.');
        }

        $data = $request->validate([
            'metodo'     => ['required','string'],
            'referencia' => ['nullable','string'],
            'notas'      => ['nullable','string'],
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

            $pago->load('asignaciones');

            // 1) Revertir cuotas según asignaciones
            foreach ($pago->asignaciones as $asig) {
                $cuota = Cuota::lockForUpdate()->findOrFail($asig->cuota_id);

                $cuota->update([
                    'total_pagado' => (float)$cuota->total_pagado - (float)$asig->capital_pagado,
                    'saldo_cuota'  => (float)$cuota->saldo_cuota + (float)$asig->capital_pagado,
                ]);

                // Recalcular estado según saldo
                $cuota->refresh();
                $estado = ((float)$cuota->saldo_cuota <= 0) ? 'pagada' :
                          (((float)$cuota->total_pagado <= 0) ? 'pendiente' : 'parcial');

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
        });

        return back()->with('success', 'Pago anulado correctamente');
    }
}

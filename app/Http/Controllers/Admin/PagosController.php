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
                'asignaciones.cuota',   // ✅ aquí está la cuota (vía asignacion_pagos)
                'recibidoPor',
                'anuladoPor',
            ])
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.pagos.index', compact('pagos'));
    }

    public function create()
    {
        // Solo préstamos activos (si quieres incluir 'en_mora', agrégalo aquí)
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
            'cuota_id'    => ['required', 'exists:cuotas,id'],
            'monto'       => ['required', 'numeric', 'min:1'],
            'metodo'      => ['required', 'string'],
            'referencia'  => ['nullable', 'string'],
            'notas'       => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($data) {

            $prestamo = Prestamo::lockForUpdate()->findOrFail($data['prestamo_id']);
            $cuota    = Cuota::lockForUpdate()->findOrFail($data['cuota_id']);

            // ✅ Validación: la cuota debe pertenecer a ese préstamo
            if ((int)$cuota->prestamo_id !== (int)$prestamo->id) {
                abort(422, 'La cuota seleccionada no pertenece a ese préstamo.');
            }

            if ($cuota->estado === 'pagada') {
                abort(400, 'La cuota ya está pagada');
            }

            if ($data['monto'] > $cuota->saldo_cuota) {
                abort(400, 'El pago excede el saldo de la cuota');
            }

            // 1) Crear el pago
            $pago = Pago::create([
                'prestamo_id'  => $prestamo->id,
                'pagado_en'    => now(),
                'metodo'       => $data['metodo'],
                'referencia'   => $data['referencia'],
                'recibido_por' => Auth::id(),
                'monto'        => $data['monto'],
                'estado'       => 'registrado',
                'notas'        => $data['notas'],
            ]);

            // 2) Crear asignación (tabla asignacion_pagos)
            AsignacionPago::create([
                'pago_id'         => $pago->id,
                'cuota_id'        => $cuota->id,
                'capital_pagado'  => $data['monto'],
                'intereses_pagado'=> 0,
                'mora_pagada'     => 0,
                'asignado_en'     => now(),
            ]);

            // 3) Actualizar cuota
            $nuevoSaldo = $cuota->saldo_cuota - $data['monto'];

            $cuota->update([
                'total_pagado' => $cuota->total_pagado + $data['monto'],
                'saldo_cuota'  => $nuevoSaldo,
                'estado'       => $nuevoSaldo == 0 ? 'pagada' : 'parcial',
                'pagado_en'    => $nuevoSaldo == 0 ? now() : null,
            ]);

            // 4) Registrar entrada en caja
            $saldoAnterior = DB::table('caja')->orderByDesc('id')->value('saldo_despues') ?? 0;

            DB::table('caja')->insert([
                'fecha'           => now(),
                'monto'           => $data['monto'],
                'direccion'       => 'IN',
                'concepto'        => 'Pago cuota #' . $cuota->numero,
                'tipo_referencia' => 'pago',
                'id_referencia'   => $pago->id,
                'creado_por'      => Auth::id(),
                'saldo_despues'   => $saldoAnterior + $data['monto'],
                'estado'          => 'normal',
                'created_at'      => now(),
            ]);
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
        $pago->load(['prestamo.cliente','asignaciones.cuota']);

        return view('admin.pagos.edit', compact('pago'));
    }

    public function update(Request $request, Pago $pago)
    {
        // Regla: si ya está anulado, no se edita
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

            $pago->load('asignaciones.cuota');

            // 1) Revertir cada cuota según su asignación (si luego permites 1 pago -> varias cuotas)
            foreach ($pago->asignaciones as $asig) {
                $cuota = Cuota::lockForUpdate()->findOrFail($asig->cuota_id);

                $cuota->update([
                    'total_pagado' => $cuota->total_pagado - $asig->capital_pagado,
                    'saldo_cuota'  => $cuota->saldo_cuota + $asig->capital_pagado,
                    'estado'       => 'pendiente',
                    'pagado_en'    => null,
                ]);
            }

            // 2) Caja OUT
            $saldoAnterior = DB::table('caja')->orderByDesc('id')->value('saldo_despues') ?? 0;

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

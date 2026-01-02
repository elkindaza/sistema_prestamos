<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AsignacionPago;
use App\Models\Pago;
use App\Models\Cuota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsignacionPagosController extends Controller
{
    public function index()
    {
        $asignaciones = AsignacionPago::with([
                'pago.prestamo.cliente',
                'cuota'
            ])
            ->orderByDesc('asignado_en')
            ->paginate(20);

        return view('admin.asignaciones.index', compact('asignaciones'));
    }

    public function create()
    {
        $pagos = Pago::where('estado', 'registrado')
            ->orderByDesc('id')
            ->get();

        $cuotas = Cuota::whereIn('estado', ['pendiente', 'parcial'])
            ->orderBy('fecha_vencimiento')
            ->get();

        return view('admin.asignaciones.create', compact('pagos', 'cuotas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pago_id'           => ['required', 'exists:pagos,id'],
            'cuota_id'          => ['required', 'exists:cuotas,id'],
            'capital_pagado'    => ['required','numeric','min:0'],
            'intereses_pagado'  => ['required','numeric','min:0'],
            'mora_pagada'       => ['required','numeric','min:0'],
        ]);

        DB::transaction(function () use ($data) {

            $total = $data['capital_pagado']
                   + $data['intereses_pagado']
                   + $data['mora_pagada'];

            $pago = Pago::lockForUpdate()->find($data['pago_id']);

            $yaAsignado = AsignacionPago::where('pago_id', $pago->id)
                ->sum(DB::raw('capital_pagado + intereses_pagado + mora_pagada'));

            if ($yaAsignado + $total > $pago->monto) {
                abort(400, 'La asignación supera el monto del pago.');
            }

            AsignacionPago::create([
                'pago_id'         => $pago->id,
                'cuota_id'        => $data['cuota_id'],
                'capital_pagado'  => $data['capital_pagado'],
                'intereses_pagado'=> $data['intereses_pagado'],
                'mora_pagada'     => $data['mora_pagada'],
                'asignado_en'     => now(),
            ]);
        });

        return redirect()
            ->route('admin.asignaciones.index')
            ->with('success', 'Asignación registrada correctamente');
    }

    public function show(AsignacionPago $asignacion)
    {
        $asignacion->load('pago.prestamo.cliente','cuota');
        return view('admin.asignaciones.show', compact('asignacion'));
    }

    public function edit(AsignacionPago $asignacion)
    {
        return view('admin.asignaciones.edit', compact('asignacion'));
    }

    public function update(Request $request, AsignacionPago $asignacion)
    {
        $data = $request->validate([
            'capital_pagado'   => ['required','numeric','min:0'],
            'intereses_pagado' => ['required','numeric','min:0'],
            'mora_pagada'      => ['required','numeric','min:0'],
        ]);

        $asignacion->update($data);

        return redirect()
            ->route('admin.asignaciones.show', $asignacion)
            ->with('success','Asignación actualizada');
    }
}

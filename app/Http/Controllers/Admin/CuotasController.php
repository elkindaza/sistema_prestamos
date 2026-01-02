<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cuota;
use App\Models\Prestamo;
use Illuminate\Http\Request;

class CuotasController extends Controller
{
    public function index(Request $request)
    {
        $prestamoId = $request->get('prestamo_id');

        $cuotas = Cuota::with(['prestamo.cliente'])
            ->when($prestamoId, fn ($q) => $q->where('prestamo_id', $prestamoId))
            ->orderByDesc('id')
            ->paginate(15);

        return view('admin.cuotas.index', compact('cuotas', 'prestamoId'));
    }

    public function create()
    {
        $prestamos = Prestamo::with('cliente')->orderByDesc('id')->get();
        return view('admin.cuotas.create', compact('prestamos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'prestamo_id' => ['required','exists:prestamos,id'],
            'numero' => ['required','integer','min:1'],
            'fecha_vencimiento' => ['required','date'],

            'capital_programado' => ['required','numeric','min:0'],
            'interes_programado' => ['required','numeric','min:0'],

            'estado' => ['required','in:pendiente,parcial,pagada,vencida'],
            'nota' => ['nullable','string'], // si NO existe "nota" en tu tabla, bórralo del form y de aquí
        ]);

        // Validación: número único por préstamo
        $yaExiste = Cuota::where('prestamo_id', $data['prestamo_id'])
            ->where('numero', $data['numero'])
            ->exists();

        if ($yaExiste) {
            return back()
                ->withErrors(['numero' => 'Ya existe esa cuota para este préstamo.'])
                ->withInput();
        }

        $totalProgramado = (float)$data['capital_programado'] + (float)$data['interes_programado'];

        // defaults coherentes
        $data['total_programado'] = $totalProgramado;
        $data['interes_pagado'] = 0;
        $data['mora_pagada'] = 0;
        $data['total_pagado'] = 0;
        $data['saldo_cuota'] = $totalProgramado;

        // OJO: tu tabla en el SQL original NO tenía "nota".
        // Si tu migración real NO incluye "nota", elimina estas 2 líneas:
        unset($data['nota']);

        $cuota = Cuota::create($data);

        return redirect()
            ->route('admin.cuotas.show', $cuota)
            ->with('success', 'Cuota creada correctamente.');
    }

    public function show(Cuota $cuota)
    {
        $cuota->load(['prestamo.cliente']);
        return view('admin.cuotas.show', compact('cuota'));
    }

    public function edit(Cuota $cuota)
    {
        $cuota->load(['prestamo.cliente']);
        return view('admin.cuotas.edit', compact('cuota'));
    }

    public function update(Request $request, Cuota $cuota)
    {
        // Si ya está pagada, normalmente se bloquea edición
        if ($cuota->estado === 'pagada') {
            return back()->with('error', 'No puedes editar una cuota pagada.');
        }

        $data = $request->validate([
            'fecha_vencimiento' => ['required','date'],

            'capital_programado' => ['required','numeric','min:0'],
            'interes_programado' => ['required','numeric','min:0'],

            'estado' => ['required','in:pendiente,parcial,pagada,vencida'],
            'nota' => ['nullable','string'], // si NO existe en tu tabla, bórralo aquí y del blade
        ]);

        $totalProgramado = (float)$data['capital_programado'] + (float)$data['interes_programado'];
        $data['total_programado'] = $totalProgramado;

        // recalcular saldo según pagos ya registrados
        $totalPagado = (float)$cuota->total_pagado;
        $data['saldo_cuota'] = max($totalProgramado - $totalPagado, 0);

        // Si la tabla no tiene "nota", elimina:
        unset($data['nota']);

        // Si marca pagada, setear fecha pagado_en si saldo ya es 0
        if ($data['estado'] === 'pagada' && $data['saldo_cuota'] > 0) {
            return back()->withErrors([
                'estado' => 'No puedes marcar como pagada si el saldo de la cuota no es 0.'
            ])->withInput();
        }

        if ($data['estado'] === 'pagada' && $data['saldo_cuota'] == 0 && !$cuota->pagado_en) {
            $data['pagado_en'] = now();
        }

        $cuota->update($data);

        return redirect()
            ->route('admin.cuotas.show', $cuota)
            ->with('success', 'Cuota actualizada correctamente.');
    }

    public function destroy(Cuota $cuota)
    {
        if ($cuota->estado !== 'pendiente') {
            return back()->with('error', 'Solo puedes eliminar cuotas en estado pendiente.');
        }

        $cuota->delete();

        return redirect()
            ->route('admin.cuotas.index', ['prestamo_id' => $cuota->prestamo_id])
            ->with('success', 'Cuota eliminada.');
    }
}

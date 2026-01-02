<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestamo;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PrestamosController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');

        $prestamos = Prestamo::with('cliente')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->whereHas('cliente', fn($c) => $c->where('nombre_completo', 'like', "%$q%"))
                       ->orWhere('id', $q)
                       ->orWhere('estado', 'like', "%$q%");
                });
            })
            ->orderByDesc('id')
            ->paginate(15);

        return view('admin.prestamos.index', compact('prestamos', 'q'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nombre_completo')->get();
        return view('admin.prestamos.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id' => ['required','exists:clientes,id'],
            'monto_principal' => ['required','numeric','min:1'],
            'meses_plazo' => ['required','integer','min:1','max:360'],
            'tasa_interes' => ['required','numeric','min:0','max:1'], // 0.0300 = 3%
            'tipo_interes' => ['required','in:mensual'],
            'tipo_cuota' => ['required','in:fija,capital_fijo'],
            'fecha_inicio' => ['required','date'],
            'fecha_primera_cuota' => ['required','date','after_or_equal:fecha_inicio'],
            'fecha_vencimiento' => ['required','date','after_or_equal:fecha_primera_cuota'],
            'frecuencia' => ['required','in:mensual,quincenal'],
            'nota' => ['nullable','string'],
        ]);

        $data['estado'] = 'en_analisis';

        $prestamo = Prestamo::create($data);

        return redirect()
            ->route('admin.prestamos.show', $prestamo)
            ->with('success', 'Préstamo creado (en análisis).');
    }

    public function show(Prestamo $prestamo)
    {
        $prestamo->load('cliente','cuotas');
        return view('admin.prestamos.show', compact('prestamo'));
    }

    public function edit(Prestamo $prestamo)
    {
        $clientes = Cliente::orderBy('nombre_completo')->get();
        return view('admin.prestamos.edit', compact('prestamo','clientes'));
    }

    public function update(Request $request, Prestamo $prestamo)
    {
        $data = $request->validate([
            'cliente_id' => ['required','exists:clientes,id'],
            'monto_principal' => ['required','numeric','min:1'],
            'meses_plazo' => ['required','integer','min:1','max:360'],
            'tasa_interes' => ['required','numeric','min:0','max:1'],
            'tipo_interes' => ['required','in:mensual'],
            'tipo_cuota' => ['required','in:fija,capital_fijo'],
            'fecha_inicio' => ['required','date'],
            'fecha_primera_cuota' => ['required','date','after_or_equal:fecha_inicio'],
            'fecha_vencimiento' => ['required','date','after_or_equal:fecha_primera_cuota'],
            'frecuencia' => ['required','in:mensual,quincenal'],

            
            'estado' => ['required','in:en_analisis,aprobado,rechazado,desembolsado,activo,en_mora,finalizado'],
            'nota_aprobacion' => ['nullable','string'],

            'nota' => ['nullable','string'],
        ]);

        
        if ($data['estado'] !== $prestamo->estado) {
            if ($data['estado'] === 'aprobado') {
                return back()->with('error', 'Para cambiar a "aprobado" usa el botón/acción Aprobar.');
            }

            if ($data['estado'] === 'activo') {
                return back()->with('error', 'Para cambiar a "activo" debes desembolsar el préstamo (acción Desembolsar).');
            }
        }

        $prestamo->update($data);

        return redirect()
            ->route('admin.prestamos.show', $prestamo)
            ->with('success', 'Préstamo actualizado.');
    }

    // ✅ Acción: aprobar (solo admin)
    public function aprobar(Request $request, Prestamo $prestamo)
    {
        if ($prestamo->estado !== 'en_analisis') {
            return back()->with('error', 'Solo se puede aprobar un préstamo en análisis.');
        }

        $data = $request->validate([
            'nota_aprobacion' => ['nullable','string'],
        ]);

        $prestamo->update([
            'estado' => 'aprobado',
            'aprobado_en' => now(),
            'aprobado_por' => Auth::id(),
            'nota_aprobacion' => $data['nota_aprobacion'] ?? null,
        ]);

        return back()->with('success', 'Préstamo aprobado.');
    }

    // ✅ Acción: desembolsar (requiere caja suficiente)
    public function desembolsar(Request $request, Prestamo $prestamo)
    {
        if ($prestamo->estado !== 'aprobado') {
            return back()->with('error', 'Solo se puede desembolsar un préstamo aprobado.');
        }

        $saldo = DB::table('caja')->orderByDesc('id')->value('saldo_despues') ?? 0;

        if ($saldo < $prestamo->monto_principal) {
            return back()->with('error', "Caja insuficiente. Saldo: $saldo, requerido: {$prestamo->monto_principal}");
        }

        DB::transaction(function () use ($prestamo, $saldo) {
            $nuevoSaldo = $saldo - $prestamo->monto_principal;

            DB::table('caja')->insert([
                'fecha' => now(),
                'monto' => $prestamo->monto_principal,
                'direccion' => 'OUT',
                'concepto' => 'Desembolso préstamo #' . $prestamo->id,
                'tipo_referencia' => 'prestamo',
                'id_referencia' => $prestamo->id,
                'creado_por' => Auth::id(),
                'doc_id' => $prestamo->documento_desembolso_id,
                'saldo_despues' => $nuevoSaldo,
                'estado' => 'normal',
                'nota' => 'Desembolso',
                'created_at' => now(),
            ]);

            $prestamo->update([
                'estado' => 'activo',
                'desembolsado_en' => now(),
                'desembolsado_por' => Auth::id(),
            ]);
        });

        return back()->with('success', 'Préstamo desembolsado y registrado en caja.');
    }

    public function destroy(Prestamo $prestamo)
    {
        if (in_array($prestamo->estado, ['activo','en_mora','finalizado'], true)) {
            return back()->with('error', 'No puedes eliminar un préstamo activo/en mora/finalizado. Recházalo o ciérralo.');
        }

        $prestamo->update(['estado' => 'rechazado']);
        return redirect()->route('admin.prestamos.index')->with('success', 'Préstamo marcado como rechazado.');
    }
}

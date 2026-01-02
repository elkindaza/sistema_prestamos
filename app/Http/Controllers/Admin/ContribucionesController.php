<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contribucion;
use App\Models\Asociado;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContribucionesController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $desde = $request->get('desde');
        $hasta = $request->get('hasta');

        $contribuciones = Contribucion::with(['asociado.user', 'adjunto'])
            ->when($q, function ($query) use ($q) {
                $query->where('id', $q)
                    ->orWhere('referencia', 'like', "%$q%")
                    ->orWhere('metodo', 'like', "%$q%")
                    ->orWhereHas('asociado.user', function ($u) use ($q) {
                        $u->where('nombre', 'like', "%$q%")
                          ->orWhere('email', 'like', "%$q%");
                    });
            })
            ->when($desde, fn($query) => $query->whereDate('aportado_en', '>=', $desde))
            ->when($hasta, fn($query) => $query->whereDate('aportado_en', '<=', $hasta))
            ->orderByDesc('aportado_en')
            ->paginate(15)
            ->withQueryString();

        return view('admin.contribuciones.index', compact('contribuciones', 'q', 'desde', 'hasta'));
    }

    public function create()
    {
        $asociados = Asociado::with('user')
            ->orderByDesc('id')
            ->get();

        $documentos = Documento::orderByDesc('id')->limit(50)->get();

        return view('admin.contribuciones.create', compact('asociados', 'documentos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'asociado_id' => ['required', 'exists:asociados,id'],
            'monto' => ['required', 'numeric', 'min:1'],
            'aportado_en' => ['required', 'date'],
            'metodo' => ['required', 'string', 'max:50'],
            'referencia' => ['nullable', 'string', 'max:100'],
            'adjunto_id' => ['nullable', 'exists:documentos,id'],
            'nota_caja' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($data) {
            // 1) Crear contribución
            $contribucion = Contribucion::create([
                'asociado_id' => $data['asociado_id'],
                'monto' => $data['monto'],
                'aportado_en' => $data['aportado_en'],
                'metodo' => $data['metodo'],
                'referencia' => $data['referencia'] ?? null,
                'adjunto_id' => $data['adjunto_id'] ?? null,
            ]);

            // 2) Registrar IN en caja (ledger)
            $saldoAnterior = DB::table('caja')->orderByDesc('id')->value('saldo_despues') ?? 0;
            $nuevoSaldo = $saldoAnterior + $contribucion->monto;

            DB::table('caja')->insert([
                'fecha' => $contribucion->aportado_en,
                'monto' => $contribucion->monto,
                'direccion' => 'IN',
                'concepto' => 'Contribución asociado #' . $contribucion->asociado_id,
                'tipo_referencia' => 'contribucion',
                'id_referencia' => $contribucion->id,
                'creado_por' => Auth::id(),
                'doc_id' => $contribucion->adjunto_id,
                'saldo_despues' => $nuevoSaldo,
                'estado' => 'normal',
                'nota' => $data['nota_caja'] ?? 'Ingreso por contribución',
                'created_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.contribuciones.index')
            ->with('success', 'Contribución registrada y reflejada en caja.');
    }

    public function show(Contribucion $contribucion)
    {
        $contribucion->load(['asociado.user', 'adjunto']);

        $movCaja = DB::table('caja')
            ->where('tipo_referencia', 'contribucion')
            ->where('id_referencia', $contribucion->id)
            ->orderByDesc('id')
            ->first();

        return view('admin.contribuciones.show', compact('contribucion', 'movCaja'));
    }

    public function edit(Contribucion $contribucion)
    {
        $contribucion->load(['asociado.user', 'adjunto']);

        $asociados = Asociado::with('user')
            ->orderByDesc('id')
            ->get();

        $documentos = Documento::orderByDesc('id')->limit(50)->get();

        return view('admin.contribuciones.edit', compact('contribucion', 'asociados', 'documentos'));
    }

    public function update(Request $request, Contribucion $contribucion)
    {
        // ✅ Regla práctica (para no romper auditoría):
        // NO editamos el monto aquí. Si te equivocas en el monto: ANULAR y crear otra.
        $data = $request->validate([
            'asociado_id' => ['required', 'exists:asociados,id'],
            'aportado_en' => ['required', 'date'],
            'metodo' => ['required', 'string', 'max:50'],
            'referencia' => ['nullable', 'string', 'max:100'],
            'adjunto_id' => ['nullable', 'exists:documentos,id'],
        ]);

        DB::transaction(function () use ($contribucion, $data) {
            $contribucion->update($data);

            // Actualizamos solo metadata del movimiento de caja (sin tocar monto / saldo)
            DB::table('caja')
                ->where('tipo_referencia', 'contribucion')
                ->where('id_referencia', $contribucion->id)
                ->update([
                    'fecha' => $contribucion->aportado_en,
                    'doc_id' => $contribucion->adjunto_id,
                    'updated_at' => now(),
                ]);
        });

        return redirect()
            ->route('admin.contribuciones.show', $contribucion)
            ->with('success', 'Contribución actualizada (metadatos).');
    }

    public function anular(Request $request, Contribucion $contribucion)
    {
        $request->validate([
            'motivo' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($contribucion, $request) {
            // Buscar el IN original
            $movOriginal = DB::table('caja')
                ->where('tipo_referencia', 'contribucion')
                ->where('id_referencia', $contribucion->id)
                ->orderByDesc('id')
                ->first();

            if (!$movOriginal) {
                abort(400, 'No se encontró movimiento de caja para esta contribución.');
            }

            // Marcar original como anulado (auditoría)
            DB::table('caja')->where('id', $movOriginal->id)->update([
                'estado' => 'anulado',
                'updated_at' => now(),
                'nota' => trim(($movOriginal->nota ?? '') . ' | ANULADO: ' . ($request->motivo ?? 'sin motivo')),
            ]);

            // Crear OUT de reverso
            $saldoAnterior = DB::table('caja')->orderByDesc('id')->value('saldo_despues') ?? 0;
            $nuevoSaldo = $saldoAnterior - $contribucion->monto;

            DB::table('caja')->insert([
                'fecha' => now(),
                'monto' => $contribucion->monto,
                'direccion' => 'OUT',
                'concepto' => 'Reverso contribución #' . $contribucion->id,
                'tipo_referencia' => 'contribucion',
                'id_referencia' => $contribucion->id,
                'creado_por' => Auth::id(),
                'doc_id' => $contribucion->adjunto_id,
                'saldo_despues' => $nuevoSaldo,
                'estado' => 'normal',
                'nota' => 'Reverso por anulación: ' . ($request->motivo ?? 'sin motivo'),
                'created_at' => now(),
            ]);
        });

        return redirect()
            ->route('admin.contribuciones.show', $contribucion)
            ->with('success', 'Contribución anulada y reversada en caja.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));
        $tipo = $request->get('tipo');
        $dir = $request->get('dir'); // IN / OUT
        $estado = $request->get('estado'); // normal / anulado
        $from = $request->get('from'); // yyyy-mm-dd
        $to = $request->get('to');     // yyyy-mm-dd

        $movimientos = Caja::query()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('concepto', 'like', "%$q%")
                      ->orWhere('tipo_referencia', 'like', "%$q%")
                      ->orWhere('id_referencia', $q)
                      ->orWhere('id', $q);
                });
            })
            ->when($tipo, fn($query) => $query->where('tipo_referencia', $tipo))
            ->when($dir, fn($query) => $query->where('direccion', $dir))
            ->when($estado, fn($query) => $query->where('estado', $estado))
            ->when($from, fn($query) => $query->whereDate('fecha', '>=', $from))
            ->when($to, fn($query) => $query->whereDate('fecha', '<=', $to))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $saldoActual = Caja::orderByDesc('id')->value('saldo_despues') ?? 0;

        return view('admin.caja.index', compact('movimientos','saldoActual','q','tipo','dir','estado','from','to'));
    }

    public function show(Caja $caja)
    {
        return view('admin.caja.show', compact('caja'));
    }
}

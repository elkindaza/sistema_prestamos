<?php

namespace App\Services\Caja;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CajaService
{
    public function registrarIngreso(float $monto, string $concepto, string $tipoReferencia, int $idReferencia): void
    {
        $this->insertMovimiento('IN', $monto, $concepto, $tipoReferencia, $idReferencia);
    }

    public function registrarSalida(float $monto, string $concepto, string $tipoReferencia, int $idReferencia): void
    {
        $this->insertMovimiento('OUT', $monto, $concepto, $tipoReferencia, $idReferencia);
    }

    private function insertMovimiento(string $direccion, float $monto, string $concepto, string $tipoReferencia, int $idReferencia): void
    {
        // lock a la última fila para evitar race condition del saldo_despues
        $ultimaCaja = DB::table('caja')
            ->orderByDesc('id')
            ->lockForUpdate()
            ->first();

        $saldoAnterior = $ultimaCaja->saldo_despues ?? 0;

        $saldoDespues = $direccion === 'IN'
            ? $saldoAnterior + $monto
            : $saldoAnterior - $monto;

        DB::table('caja')->insert([
            'fecha'           => now(),
            'monto'           => $monto,
            'direccion'       => $direccion,
            'concepto'        => $concepto,
            'tipo_referencia' => $tipoReferencia,
            'id_referencia'   => $idReferencia,
            'creado_por'      => Auth::id(),
            'saldo_despues'   => $saldoDespues,
            'estado'          => 'normal',
            'created_at'      => now(),
        ]);
    }
}

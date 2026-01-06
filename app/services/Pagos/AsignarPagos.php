<?php

namespace App\Services\Pagos;

use App\Models\Cuota;
use App\Models\Pago;
use App\Models\AsignacionPago;

class AsignarPagos
{
    /**
     * Asigna un pago a las cuotas del préstamo en orden (vencimiento, luego número).
     * Por ahora todo va como capital_pagado (intereses/mora = 0).
     *
     * @return float monto que NO se pudo asignar (saldo a favor), si sobra.
     */
    public function asignarEnOrden(Pago $pago, ?int $cuotaForzadaId = null): float
    {
        $montoRestante = (float) $pago->monto;

        // 1) Si viene una cuota forzada, se aplica primero ahí (opcional)
        if (!empty($cuotaForzadaId)) {
            $cuota = Cuota::lockForUpdate()->findOrFail($cuotaForzadaId);

            if ((int) $cuota->prestamo_id !== (int) $pago->prestamo_id) {
                abort(422, 'La cuota seleccionada no pertenece a ese préstamo.');
            }
            if ($cuota->estado === 'pagada' || (float) $cuota->saldo_cuota <= 0) {
                abort(422, 'La cuota seleccionada ya está pagada.');
            }

            $montoRestante = $this->aplicarMontoACuota($pago->id, $cuota, $montoRestante);
        }

        // 2) Asignación automática a cuotas pendientes/parciales/vencidas
        if ($montoRestante > 0) {
            $cuotas = Cuota::where('prestamo_id', $pago->prestamo_id)
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

        return $montoRestante;
    }

    private function aplicarMontoACuota(int $pagoId, Cuota $cuota, float $montoDisponible): float
    {
        $saldo = (float) $cuota->saldo_cuota;
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
        $nuevoTotalPagado = (float) $cuota->total_pagado + $aplicar;

        $cuota->update([
            'total_pagado' => $nuevoTotalPagado,
            'saldo_cuota'  => $nuevoSaldo,
            'estado'       => $nuevoSaldo <= 0 ? 'pagada' : 'parcial',
            'pagado_en'    => $nuevoSaldo <= 0 ? now() : null,
        ]);

        return $montoDisponible - $aplicar;
    }
}

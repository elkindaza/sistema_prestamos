<?php

namespace App\Services\Pagos;

use App\Models\Pago;
use App\Models\Cuota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\Caja\CajaService;
use App\Exceptions\ReglaNegocioException;
use App\Services\Prestamos\ActualizarEstadoPrestamo;


class AnularPago
{
    public function __construct(
        private CajaService $cajaService,
        private ActualizarEstadoPrestamo $actualizarEstadoPrestamo
    ) {}
    /**
     * Anula un pago si NO hay pagos posteriores en el mismo préstamo.
     */
    public function anular(Pago $pago, ?string $motivo = null): void
    {
        if ($pago->estado === 'anulado') {
            throw new ReglaNegocioException('El pago ya está anulado.');
        }

        // Bloquear si hay pagos posteriores del mismo préstamo
        $hayPosteriores = Pago::where('prestamo_id', $pago->prestamo_id)
            ->where('estado', 'registrado')
            ->where(function ($q) use ($pago) {
                // posterior por fecha, o mismo instante pero id mayor (por seguridad)
                $q->where('pagado_en', '>', $pago->pagado_en)
                    ->orWhere(function ($q2) use ($pago) {
                        $q2->where('pagado_en', '=', $pago->pagado_en)
                            ->where('id', '>', $pago->id);
                    });
            })
            ->exists();

        if ($hayPosteriores) {
            throw new ReglaNegocioException('No puedes anular este pago porque existen pagos posteriores del mismo préstamo.');
        }

        DB::transaction(function () use ($pago, $motivo) {

            // Cargar asignaciones (cuotas )
            $pago->load('asignaciones');

            if ($pago->asignaciones->isEmpty()) {
                throw new ReglaNegocioException('Este pago no tiene asignaciones. No se puede anular de forma segura.');
            }

            // 1) Revertir cuotas según asignaciones
            foreach ($pago->asignaciones as $asig) {
                $cuota = Cuota::lockForUpdate()->findOrFail($asig->cuota_id);

                $revertir = (float)$asig->capital_pagado
                    + (float)$asig->intereses_pagado
                    + (float)$asig->mora_pagada;

                // Revertimos acumulados (por ahora tu cuota usa total_pagado + saldo_cuota)
                $nuevoTotalPagado = (float)$cuota->total_pagado - $revertir;
                $nuevoSaldo = (float)$cuota->saldo_cuota + $revertir;

                if ($nuevoTotalPagado < 0) $nuevoTotalPagado = 0; // guard rails
                // Nota: saldo podría quedar > total_programado si había inconsistencias previas.
                // Lo dejamos así para no ocultar errores; luego lo auditamos.

                // Recalcular estado
                $estado = 'pendiente';
                if ($nuevoSaldo <= 0.00001) {
                    $estado = 'pagada';
                } elseif ($nuevoTotalPagado > 0.00001) {
                    $estado = 'parcial';
                } else {
                    $estado = 'pendiente';
                }

                $cuota->update([
                    'total_pagado' => $nuevoTotalPagado,
                    'saldo_cuota'  => $nuevoSaldo,
                    'estado'       => $estado,
                    'pagado_en'    => $estado === 'pagada' ? ($cuota->pagado_en ?? now()) : null,
                    // Si más adelante usas interes_pagado/mora_pagada en cuota, aquí los reviertes también.
                ]);
            }

            // 2) Registrar salida en caja (OUT)
            $this->cajaService->registrarSalida(
                (float)$pago->monto,
                'Anulación pago #' . $pago->id,
                'pago',
                $pago->id
            );

            // 3) Marcar pago como anulado
            $pago->update([
                'estado'         => 'anulado',
                'anulado_en'     => now(),
                'anulado_por'    => Auth::id(),
                'motivo_anulacion' => $motivo, // si tu columna existe (en tu SQL sí existe)
            ]);
        });

        $prestamo = $pago->prestamo ?? \App\Models\Prestamo::findOrFail($pago->prestamo_id);
        $this->actualizarEstadoPrestamo->ejecutar($prestamo);
    }
}

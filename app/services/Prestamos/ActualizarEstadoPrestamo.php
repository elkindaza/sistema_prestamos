<?php

namespace App\Services\Prestamos;

use App\Models\Prestamo;
use Carbon\Carbon;

class ActualizarEstadoPrestamo
{
    /**
     * Reglas:
     * - Si todas las cuotas están pagadas (saldo_cuota = 0) => finalizado
     * - Si existe alguna cuota vencida con saldo > 0 => en_mora
     * - Si hay cuotas pendientes/parciales pero ninguna vencida => activo
     *
     * NOTA:
     * - No toca estados "en_analisis", "aprobado", "rechazado", "desembolsado"
     * - Solo aplica a préstamos ya operativos: activo/en_mora/finalizado
     */
    public function ejecutar(Prestamo $prestamo): void
    {
        // Solo aplicamos a estos (los "operativos")
        if (!in_array($prestamo->estado, ['activo', 'en_mora', 'finalizado'], true)) {
            return;
        }

        $hoy = Carbon::today();

        // 1) ¿Hay cuotas con saldo > 0?
        $pendientes = $prestamo->cuotas()
            ->where('saldo_cuota', '>', 0)
            ->exists();

        if (!$pendientes) {
            // No queda saldo en ninguna cuota => finalizado
            if ($prestamo->estado !== 'finalizado') {
                $prestamo->update(['estado' => 'finalizado']);
            }
            return;
        }

        // 2) ¿Hay cuota vencida con saldo > 0?
        $hayVencidasConSaldo = $prestamo->cuotas()
            ->where('saldo_cuota', '>', 0)
            ->whereDate('fecha_vencimiento', '<', $hoy)
            ->exists();

        if ($hayVencidasConSaldo) {
            if ($prestamo->estado !== 'en_mora') {
                $prestamo->update(['estado' => 'en_mora']);
            }
            return;
        }

        // 3) Si hay pendientes pero ninguna vencida => activo
        if ($prestamo->estado !== 'activo') {
            $prestamo->update(['estado' => 'activo']);
        }
    }
}

<?php

namespace App\Services\Cuotas;

use App\Models\Prestamo;
use App\Models\Cuota;
use App\Exceptions\ReglaNegocioException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\Prestamos\ActualizarEstadoPrestamo;


class GenerarCuotas
{
    /**
     * Genera cuotas para un préstamo.
     * - Bloquea si ya existen cuotas o si hay pagos registrados.
     */
    public function __construct(
   private ActualizarEstadoPrestamo $actualizarEstadoPrestamo
) {}
    public function generar(Prestamo $prestamo): void
    {
        // Bloquear si ya hay pagos
        $tienePagos = $prestamo->pagos()
            ->where('estado', 'registrado')
            ->exists();

        if ($tienePagos) {
            throw new ReglaNegocioException('No se pueden generar/editar cuotas porque el préstamo ya tiene pagos registrados.');
        }

        // Bloquear si ya existen cuotas (para evitar duplicados)
        $yaTieneCuotas = $prestamo->cuotas()->exists();
        if ($yaTieneCuotas) {
            throw new ReglaNegocioException('Este préstamo ya tiene cuotas generadas.');
        }

        $plazo = (int)$prestamo->meses_plazo;
        $principal = (float)$prestamo->monto_principal;
        $tasa = (float)$prestamo->tasa_interes; // ejemplo: 0.0300 = 3% mensual

        if ($plazo <= 0 || $principal <= 0 || $tasa < 0) {
            throw new ReglaNegocioException('Datos inválidos para generar cuotas (plazo, principal o tasa).');
        }

        // Fecha primera cuota
        $fecha = Carbon::parse($prestamo->fecha_primera_cuota)->startOfDay();

        // Tipo de cuota: en tu BD tienes fija/capital_fijo. Vamos a ampliar a solo_interes.
        $tipo = $prestamo->tipo_cuota; // 'capital_fijo' | 'fija' | (nuevo) 'solo_interes'

        DB::transaction(function () use ($prestamo, $plazo, $principal, $tasa, $fecha, $tipo) {
            if ($tipo === 'solo_interes') {
                $this->generarSoloInteres($prestamo, $plazo, $principal, $tasa, $fecha);
                return;
            }

            // default: capital_fijo
            $this->generarCapitalFijo($prestamo, $plazo, $principal, $tasa, $fecha);
        });
        $this->actualizarEstadoPrestamo->ejecutar($prestamo);

    }

    private function generarCapitalFijo(Prestamo $prestamo, int $plazo, float $principal, float $tasa, Carbon $fechaPrimera): void
    {
        $capitalBase = $principal / $plazo;

        // Para que la suma de capital sea exacta, ajustamos la última cuota por redondeos
        $saldo = $principal;

        for ($n = 1; $n <= $plazo; $n++) {
            $fechaVenc = (clone $fechaPrimera)->addMonths($n - 1);

            // Capital cuota (última ajusta para cerrar saldo)
            $capital = ($n < $plazo) ? $capitalBase : $saldo;
            $capital = round($capital, 2);

            // Interés sobre saldo antes de pagar
            $interes = round($saldo * $tasa, 2);

            $total = round($capital + $interes, 2);

            Cuota::create([
                'prestamo_id'        => $prestamo->id,
                'numero'             => $n,
                'fecha_vencimiento'  => $fechaVenc->toDateString(),
                'capital_programado' => $capital,
                'interes_programado' => $interes,
                'total_programado'   => $total,
                'interes_pagado'     => 0,
                'mora_pagada'        => 0,
                'total_pagado'       => 0,
                'saldo_cuota'        => $total,
                'estado'             => 'pendiente',
                'pagado_en'          => null,
            ]);

            // Disminuye saldo por capital (no por interés)
            $saldo = round($saldo - $capital, 2);
            if ($saldo < 0) $saldo = 0;
        }
        $this->actualizarEstadoPrestamo->ejecutar($prestamo);

    }

    private function generarSoloInteres(Prestamo $prestamo, int $plazo, float $principal, float $tasa, Carbon $fechaPrimera): void
    {
        // Cuotas 1..(plazo-1): solo interés
        for ($n = 1; $n <= $plazo; $n++) {
            $fechaVenc = (clone $fechaPrimera)->addMonths($n - 1);

            $capital = ($n < $plazo) ? 0.00 : round($principal, 2);
            $interes = round($principal * $tasa, 2);

            $total = round($capital + $interes, 2);

            Cuota::create([
                'prestamo_id'        => $prestamo->id,
                'numero'             => $n,
                'fecha_vencimiento'  => $fechaVenc->toDateString(),
                'capital_programado' => $capital,
                'interes_programado' => $interes,
                'total_programado'   => $total,
                'interes_pagado'     => 0,
                'mora_pagada'        => 0,
                'total_pagado'       => 0,
                'saldo_cuota'        => $total,
                'estado'             => 'pendiente',
                'pagado_en'          => null,
            ]);
        }
        $this->actualizarEstadoPrestamo->ejecutar($prestamo);

    }
}

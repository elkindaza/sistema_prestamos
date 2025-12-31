<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asignacion_pagos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('pago_id');
            $table->unsignedBigInteger('cuota_id');

            $table->decimal('capital_pagado', 14, 2)->default(0.00);
            $table->decimal('intereses_pagado', 14, 2)->default(0.00);
            $table->decimal('mora_pagada', 14, 2)->default(0.00);

            $table->dateTime('asignado_en');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('pago_id')->references('id')->on('pagos');
            $table->foreign('cuota_id')->references('id')->on('cuotas');

            $table->index('pago_id');
            $table->index('cuota_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion_pagos');
    }
};

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
        Schema::create('backups', function (Blueprint $table) {
            $table->id();

            $table->dateTime('creado_en');
            $table->unsignedBigInteger('creado_por');
            $table->string('ubicacion', 255);
            $table->unsignedBigInteger('tamano');
            $table->string('estado', 30)->default('creado');

            $table->timestamp('created_at')->useCurrent();

            $table->foreign('creado_por')->references('id')->on('users');
            $table->index('creado_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backups');
    }
};

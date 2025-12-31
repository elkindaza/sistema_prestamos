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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('usuario_id');
            $table->string('tipo', 30);
            $table->string('titulo', 150);
            $table->text('body');
            $table->json('data_json')->nullable();

            $table->dateTime('enviado_en');
            $table->dateTime('leido_en')->nullable();
            $table->string('estado', 30)->default('enviada');

            $table->timestamp('created_at')->useCurrent();

            $table->foreign('usuario_id')->references('id')->on('users');
            $table->index('usuario_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};

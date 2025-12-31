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
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->string('ruta_archivo', 255);
            $table->string('nombre', 150);
            $table->unsignedBigInteger('tamano');
            $table->unsignedBigInteger('subido_por');
            $table->dateTime('subido_en');
            $table->string('mime_type', 100)->nullable();

            $table->timestamp('created_at')->nullable()->useCurrent();

            $table->foreign('subido_por')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};

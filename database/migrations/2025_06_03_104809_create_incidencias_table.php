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
        Schema::create('incidencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained()->onDelete('cascade');
            $table->foreignId('empresa_id')->constrained()->onDelete('cascade');
            $table->date('fecha'); // Fecha del día que olvidó fichar
            $table->time('hora'); // Hora estimada del fichaje
            $table->enum('tipo', ['entrada', 'salida']); // Tipo de fichaje
            $table->text('motivo');
            $table->string('estado')->default('pendiente'); // pendiente, aprobado, rechazado, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidencias');
    }
};

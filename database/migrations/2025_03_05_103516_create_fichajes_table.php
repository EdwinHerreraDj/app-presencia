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
        Schema::create('fichajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade'); 
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade'); 
            $table->enum('tipo', ['entrada', 'salida']); 
            $table->timestamp('fecha_hora')->default(now()); 
            $table->decimal('latitud', 10, 6)->nullable(); 
            $table->decimal('longitud', 10, 6)->nullable(); 
            $table->boolean('dentro_rango')->default(true); 
            $table->string('dispositivo')->nullable(); 
            $table->string('navegador')->nullable(); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fichajes');
    }
};

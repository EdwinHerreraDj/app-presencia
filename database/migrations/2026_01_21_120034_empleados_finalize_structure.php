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
        Schema::table('empleados', function (Blueprint $table) {

            // user_id obligatorio + unique
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->unique('user_id');

            // dni obligatorio + unique
            $table->string('dni')->nullable(false)->change();
            $table->unique('dni');

            // FK
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            // eliminar columnas legacy
            $table->dropColumn(['email', 'password', 'remember_token']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            // eliminar FK y unique
            $table->dropForeign(['user_id']);
            $table->dropUnique(['user_id']);
            $table->dropUnique(['dni']);

            // user_id nullable
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // dni nullable
            $table->string('dni')->nullable()->change();

            // agregar columnas legacy
            $table->string('email')->unique()->after('DNI');
            $table->string('password')->after('email');
            $table->rememberToken()->after('password');
        });
    }
};

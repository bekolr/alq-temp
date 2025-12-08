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
        Schema::table('estadias', function (Blueprint $table) {
            //
               $table->string('tipo_viaje')->nullable()->after('monto_total');
        $table->string('acompanante_nombre')->nullable()->after('tipo_viaje');
        $table->string('acompanante_dni', 20)->nullable()->after('acompanante_nombre');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estadias', function (Blueprint $table) {
            //
               $table->dropColumn(['tipo_viaje', 'acompanante_nombre', 'acompanante_dni']);
        });
    }
};

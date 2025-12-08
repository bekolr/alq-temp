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
        Schema::create('estadias', function (Blueprint $table) {
            $table->id();
             $table->foreignId('inquilino_id')->constrained('inquilinos');
        $table->foreignId('departamento_id')->constrained('departamentos');
        $table->date('fecha_ingreso');
        $table->date('fecha_egreso');
        $table->decimal('monto_total', 12, 2);
        $table->enum('estado', ['reservada', 'checkin', 'checkout'])
              ->default('reservada');
        $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estadias');
    }
};

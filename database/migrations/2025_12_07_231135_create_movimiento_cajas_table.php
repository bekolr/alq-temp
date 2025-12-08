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
        Schema::create('movimiento_cajas', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['ingreso', 'egreso']);
        $table->string('concepto');
        $table->dateTime('fecha')->default(DB::raw('CURRENT_TIMESTAMP'));
        $table->decimal('monto', 12, 2);
        $table->foreignId('estadia_id')->nullable()->constrained('estadias');
        $table->string('medio_pago')->nullable(); // efectivo, transferencia, etc.
        $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_cajas');
    }
};

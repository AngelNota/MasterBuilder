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
        Schema::create('compatibility_rules', function (Blueprint $table) {
            $table->id();
            $table->string('component_type_from'); // 'procesador', 'ram', 'motherboard', etc
            $table->string('spec_from'); // 'AM5', 'DDR5', 'socket', 'ram_type'
            $table->string('component_type_to'); // Componente que debe coincidir
            $table->string('spec_to'); // Especificación requerida
            $table->boolean('compatible')->default(true); // true=compatible, false=incompatible
            $table->text('message')->nullable(); // Mensaje para el usuario
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compatibility_rules');
    }
};

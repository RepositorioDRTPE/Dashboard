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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); 
            $table->string('event_code')->unique(); // El código que solo ven admin y usuario 
            $table->string('poi_code')->nullable();
            $table->string('description'); 
            $table->string('unit_measure')->default('PERSONAS');
            $table->integer('goal_people'); // Meta de personas (ej: 500) 
            $table->softDeletes(); // CRUCIAL para la función "Restaurar" 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

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
    Schema::create('sub_events', function (Blueprint $table) {
        $table->id();
        $table->foreignId('event_id')->constrained()->onDelete('cascade');
        $table->string('report_title'); // Título específico: "Capacitación en colegio..."
        $table->date('event_date');
        $table->integer('attendees_count'); 
        $table->text('comment')->nullable();
        $table->string('youtube_url')->nullable();
        $table->json('photos')->nullable(); // Guardaremos los nombres de archivos
        $table->json('photo_priority')->nullable(); // Para guardar el orden (1, 2, 3...)
        $table->softDeletes();
        $table->timestamps();
    });
}



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_events');
    }
};

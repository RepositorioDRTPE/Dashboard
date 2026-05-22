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
    Schema::create('announcements', function (Blueprint $table) {
        $table->id();
        $table->string('title');                  // Título del comunicado
        $table->text('description')->nullable();      // Resumen corto
        $table->string('file_path');              // Ruta del archivo
        $table->enum('file_type', ['pdf', 'image']); // Tipo detectado automáticamente
        $table->date('published_at');             // Fecha de inicio de publicación
        $table->date('expired_at');               // Fecha de vencimiento
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};

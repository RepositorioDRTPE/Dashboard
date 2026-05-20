

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
        Schema::create('photo_reports', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['evento', 'difusion']); // Clasificación solicitada
            $table->string('title');                      // Titular informativo
            $table->text('description');                  // Reseña detallada
            $table->json('photos');                       // Array de rutas de imágenes guardado como JSON
            $table->timestamps();                         // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_reports');
    }
};


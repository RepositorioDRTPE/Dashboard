<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workshops', function (Blueprint $table) {
            // Documento principal para saber qué hacen (Aplica a Ambos)
            $table->string('document_path')->nullable()->after('description');
            
            // Archivo de Bases o Requisitos (Solo para Capacitaciones)
            $table->string('requirements_path')->nullable()->after('document_path');
        });
    }

    public function down(): void
    {
        Schema::table('workshops', function (Blueprint $table) {
            $table->dropColumn(['document_path', 'requirements_path']);
        });
    }
};

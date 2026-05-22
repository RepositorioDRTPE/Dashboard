<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Añadimos el campo de financiamiento controlado por un ENUM seguro
            $table->enum('funding_source', ['gobierno_regional', 'gobierno_central'])
                  ->default('gobierno_regional')
                  ->after('unit_measure'); 
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('funding_source');
        });
    }
};

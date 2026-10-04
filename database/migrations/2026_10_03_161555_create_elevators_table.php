<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('elevators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->string('rae_code')->unique(); // Registro de Aparato Elevador
            $table->string('brand'); // Marca (Otis, Thyssen, Schindler, etc.)
            $table->string('model')->nullable();
            $table->integer('stops_count')->default(2); // Número de paradas
            $table->integer('max_load_kg')->nullable(); // Carga máx kg
            $table->string('type_maneuver')->nullable(); // Universal, Colectiva, etc.
            $table->date('installation_date')->nullable();
            $table->date('next_ite_date')->nullable(); // Próxima Inspección Técnica
            $table->enum('status', ['active', 'maintenance', 'stopped'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elevators');
    }
};
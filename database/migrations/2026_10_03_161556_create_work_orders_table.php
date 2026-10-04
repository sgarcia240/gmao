<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('elevator_id')->constrained()->onDelete('cascade');
            $table->foreignId('technician_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('type', ['corrective', 'preventive', 'inspection', 'assembly']);
            $table->enum('priority', ['normal', 'urgent', 'person_trapped'])->default('normal');
            $table->enum('status', ['pending', 'assigned', 'in_progress', 'completed', 'invoiced'])->default('pending');
            $table->text('issue_description'); // Descripción de la avería/solicitud
            $table->text('work_done')->nullable(); // Solución realizada por el técnico
            $table->string('failure_category')->nullable(); // Categoría: Puertas, Cuadro, Motor, etc.
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->string('client_signature')->nullable(); // Ruta de la firma guardada
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};

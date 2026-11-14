<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;
    
    public function up(): void
    {
        // 1. Ficha técnica de Toma de Datos en campo (vinculada al ascensor)
        if (!Schema::hasTable('elevator_surveys')) {
            Schema::create('elevator_surveys', function (Blueprint $table) {
                $table->id();
                $table->foreignId('elevator_id')->constrained()->cascadeOnDelete();
                $table->integer('stops')->nullable();               // Paradas / Alturas
                $table->string('machine_type')->nullable();         // Máquina
                $table->string('controller_type')->nullable();      // Cuadro de maniobra
                $table->string('door_type')->nullable();            // Tipo de puertas
                $table->string('push_buttons_type')->nullable();    // Botoneras
                $table->text('technical_notes')->nullable();        // Observaciones de hueco/foso/DTU
                $table->timestamps();
            });
        }

        // 2. Ampliar tabla work_orders con campos de planificación y presupuesto ganador
        Schema::table('work_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('work_orders', 'installer_name')) {
                $table->string('installer_name')->nullable();
            }
            if (!Schema::hasColumn('work_orders', 'scheduled_date')) {
                $table->date('scheduled_date')->nullable();
            }
            if (!Schema::hasColumn('work_orders', 'accepted_budget_amount')) {
                $table->decimal('accepted_budget_amount', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('work_orders', 'budget_provider')) {
                $table->string('budget_provider')->nullable();
            }
        });

        // 3. Fotografías del estado actual por OT
        if (!Schema::hasTable('work_order_photos')) {
            Schema::create('work_order_photos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();
                $table->string('photo_path');
                $table->string('component_tag')->default('General');
                $table->string('notes')->nullable();
                $table->timestamps();
            });
        }

        // 4. Ofertas y presupuestos recibidos de proveedores por OT
        if (!Schema::hasTable('work_order_quotes')) {
            Schema::create('work_order_quotes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();
                $table->string('provider_name');
                $table->decimal('amount', 10, 2);
                $table->string('file_path')->nullable();
                $table->boolean('is_selected')->default(false);
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // 5. Partidas de reforma seleccionadas y control de recepción de material
        if (!Schema::hasTable('reform_items')) {
            Schema::create('reform_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('work_order_id')->constrained()->cascadeOnDelete();
                $table->string('component_name');
                $table->boolean('is_selected')->default(false);
                $table->boolean('material_received')->default(false);
                $table->date('received_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reform_items');
        Schema::dropIfExists('work_order_quotes');
        Schema::dropIfExists('work_order_photos');
        Schema::dropIfExists('elevator_surveys');
        
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn(['installer_name', 'scheduled_date', 'accepted_budget_amount', 'budget_provider']);
        });
    }
};
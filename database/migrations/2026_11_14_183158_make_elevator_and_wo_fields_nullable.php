<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;
    
    public function up(): void
    {
        Schema::table('elevators', function (Blueprint $table) {
            foreach (['brand', 'model', 'type_maneuver'] as $col) {
                if (Schema::hasColumn('elevators', $col)) {
                    $table->string($col)->nullable()->change();
                }
            }
            foreach (['stops_count', 'max_load_kg'] as $col) {
                if (Schema::hasColumn('elevators', $col)) {
                    $table->integer($col)->nullable()->change();
                }
            }
        });

        Schema::table('work_orders', function (Blueprint $table) {
            foreach (['title', 'code', 'priority', 'type', 'status'] as $col) {
                if (Schema::hasColumn('work_orders', $col)) {
                    $table->string($col)->nullable()->change();
                }
            }
        });
    }

    public function down(): void
    {
        //
    }
};
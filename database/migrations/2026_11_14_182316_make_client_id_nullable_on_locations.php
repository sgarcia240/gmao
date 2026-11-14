<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;
    
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            if (Schema::hasColumn('locations', 'client_id')) {
                $table->unsignedBigInteger('client_id')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        //
    }
};
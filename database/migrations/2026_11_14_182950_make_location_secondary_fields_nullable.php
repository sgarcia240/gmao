<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            foreach (['city', 'postal_code', 'province', 'country', 'contact_name', 'contact_phone', 'notes'] as $column) {
                if (Schema::hasColumn('locations', $column)) {
                    $table->string($column)->nullable()->change();
                }
            }
        });
    }

    public function down(): void
    {
        //
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('work_orders', 'client_signature')) {
                $table->string('client_signature')->nullable()->after('status');
            }
            if (!Schema::hasColumn('work_orders', 'signed_at')) {
                $table->timestamp('signed_at')->nullable()->after('client_signature');
            }
        });
    }

    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn(array_filter(['client_signature', 'signed_at'], fn($col) => Schema::hasColumn('work_orders', $col)));
        });
    }
};
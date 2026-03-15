<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->after('package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->nullable()->after('project_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
            $table->dropForeign(['invoice_id']);
            $table->foreignId('package_id')->nullable(false)->change();
        });
    }
};

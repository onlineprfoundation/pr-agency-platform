<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->unique(); // e.g. online-pr/proposals
            $table->string('name');
            $table->string('version')->default('1.0.0');
            $table->boolean('enabled')->default(true);
            $table->json('config')->nullable(); // module-specific settings
            $table->string('source')->default('official'); // official, marketplace (future)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};

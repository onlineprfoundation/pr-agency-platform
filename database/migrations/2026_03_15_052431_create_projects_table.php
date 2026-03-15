<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('status')->default('draft'); // draft, active, review, completed, cancelled
            $table->unsignedInteger('value_cents')->nullable(); // total project value
            $table->date('due_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('style_guide')->nullable(); // message/style preferences
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

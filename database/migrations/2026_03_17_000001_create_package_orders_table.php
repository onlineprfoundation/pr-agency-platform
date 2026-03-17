<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email');
            $table->string('status')->default('pending_submission'); // pending_submission, submitted, in_progress, completed
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('featured_image_path')->nullable();
            $table->string('live_link')->nullable();
            $table->string('access_token', 64)->unique()->nullable();
            $table->timestamps();
        });

        Schema::create('package_order_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_order_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_order_documents');
        Schema::dropIfExists('package_orders');
    }
};

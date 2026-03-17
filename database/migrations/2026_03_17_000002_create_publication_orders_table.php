<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publication_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publication_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email');
            $table->string('source')->default('purchase'); // purchase, quote_request
            $table->string('status')->default('pending_submission'); // pending_submission, submitted, in_progress, completed
            $table->unsignedInteger('amount_cents')->nullable();
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('featured_image_path')->nullable();
            $table->string('live_link')->nullable();
            $table->string('access_token', 64)->unique()->nullable();
            $table->timestamps();
        });

        Schema::create('publication_order_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publication_order_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publication_order_documents');
        Schema::dropIfExists('publication_orders');
    }
};

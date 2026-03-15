<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->decimal('price_usd', 10, 2)->nullable()->after('link');
            $table->string('words_allowed')->nullable()->after('price_usd');
            $table->unsignedInteger('backlinks_count')->nullable()->after('words_allowed');
            $table->string('tat')->nullable()->after('backlinks_count'); // Turnaround Time
            $table->boolean('indexed')->default(false)->after('tat');
            $table->boolean('dofollow')->default(false)->after('indexed');
            $table->string('genre')->nullable()->after('dofollow');
            $table->text('disclaimer')->nullable()->after('genre');
            $table->string('region')->nullable()->after('disclaimer');
            $table->unsignedTinyInteger('da')->nullable()->after('region'); // Domain Authority (0-100)
            $table->unsignedInteger('traffic')->nullable()->after('da');
            $table->timestamp('last_modified_at')->nullable()->after('traffic');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->dropColumn([
                'price_usd', 'words_allowed', 'backlinks_count', 'tat',
                'indexed', 'dofollow', 'genre', 'disclaimer', 'region',
                'da', 'traffic', 'last_modified_at',
            ]);
        });
    }
};

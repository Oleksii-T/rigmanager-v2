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
        Schema::create('scraper_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scraper_id')->constrained()->onDelete('cascade');
            $table->smallInteger('status');
            $table->smallInteger('scraped')->default(0);
            $table->smallInteger('max')->nullable();
            $table->smallInteger('scrape_limit')->nullable();
            $table->boolean('scraper_debug_enabled')->default(false);
            $table->boolean('only_count')->default(false);
            $table->boolean('sanitize_html')->default(false);
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scraper_runs');
    }
};

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
        Schema::create('scraper_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('run_id')->constrained('scraper_runs')->onDelete('cascade');
            $table->smallInteger('status');
            $table->string('url');
            $table->json('data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scraper_posts');
    }
};

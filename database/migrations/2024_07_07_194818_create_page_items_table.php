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
        Schema::create('page_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('group');
            $table->unsignedInteger('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_items');
    }
};

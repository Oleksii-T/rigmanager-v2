<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('origin_lang');
            $table->string('status')->default('pending');
            $table->string('type');
            $table->string('condition')->nullable();
            $table->string('duration');
            $table->string('cost_per')->nullable();
            $table->boolean('auto_translate')->default(true);
            $table->boolean('is_double_cost')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_trashed')->default(false);
            $table->boolean('is_urgent')->default(false);
            $table->boolean('is_import')->default(false);
            $table->string('amount')->nullable();
            $table->string('country')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('manufacture_date')->nullable();
            $table->string('part_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};

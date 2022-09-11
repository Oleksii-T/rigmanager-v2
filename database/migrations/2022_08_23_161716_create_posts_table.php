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
            $table->string('condition');
            $table->string('duration');
            $table->string('is_active')->default(false);
            $table->string('is_urgent')->default(false);
            $table->string('is_import')->default(false);
            $table->unsignedInteger('amount')->nullable();
            $table->string('country')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('manufacture_date')->nullable();
            $table->string('part_number')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->decimal('cost_usd', 10, 2)->nullable();
            $table->string('currency')->nullable();
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

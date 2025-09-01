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
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->integer('points_cost');
            $table->string('image_url');
            $table->string('category');
            $table->string('reward_type'); // gift_card, in_app, booster, etc.
            $table->json('reward_data')->nullable(); // For storing reward-specific data
            $table->boolean('is_active')->default(true);
            $table->integer('stock')->nullable(); // Null means unlimited
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};

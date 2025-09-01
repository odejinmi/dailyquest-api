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
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->text('full_description');
            $table->json('steps');
            $table->integer('points');
            $table->integer('estimated_minutes');
            $table->string('icon');
            $table->string('image_url');
            $table->string('category');
            $table->boolean('is_active')->default(true);
            $table->date('available_from')->nullable();
            $table->date('available_until')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};

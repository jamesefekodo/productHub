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
        Schema::create('homepage_sliders', function (Blueprint $table) {
            $table->id();
            $table->string('background_image');
            $table->string('header');
            $table->string('header_zh')->nullable();
            $table->string('header_fr')->nullable();
            $table->text('link')->nullable();
            $table->integer('order');
            $table->text('description')->nullable();
            $table->text('description_zh')->nullable();
            $table->text('description_fr')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_sliders');
    }
};

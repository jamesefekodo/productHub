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
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('sub_category');
            $table->string('sub_category_zh')->nullable();
            $table->string('sub_category_fr')->nullable();
            $table->string('category'); //foreign
            $table->text('meta_title')->nullable();
            $table->text('meta_title_zh')->nullable();
            $table->text('meta_title_fr')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_description_zh')->nullable();
            $table->text('meta_description_fr')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();



            $table->foreign('category')->references('slug')->on('categories')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_categories');
    }
};

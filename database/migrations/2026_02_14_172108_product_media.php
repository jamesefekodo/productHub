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
        Schema::create('product_media', function (Blueprint $table) {

            $table->id();
            $table->string('product_slug')->nullable();
            $table->string('variant_slug')->nullable();

            $table->string('file_name');
            $table->enum('file_type', ['image', 'video']);

            // UI control
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);

            $table->timestamps();

            // Ref  for foreign
            $table->foreign('product_slug')->references('slug')->on('products')->onDelete('cascade');
            $table->foreign('variant_slug')->references('slug')->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_media');
    }
};

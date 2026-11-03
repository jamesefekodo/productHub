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
        Schema::create('quote_items', function (Blueprint $table) {
            $table->id();
            $table->string('quote_request_slug');
            $table->string('product_slug');
            $table->string('variant_slug')->nullable();
            $table->integer('quantity')->default(1);
            $table->timestamps();


            $table->foreign('quote_request_slug')->references('slug')->on('quote_requests')->onDelete('cascade');
            $table->foreign('product_slug')->references('slug')->on('products')->onDelete('cascade');
             $table->foreign('variant_slug')->references('slug')->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_items');
    }
};

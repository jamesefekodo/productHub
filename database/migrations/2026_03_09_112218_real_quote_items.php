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
        Schema::create('real_quote_items', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number');
            $table->string('product_slug');
            $table->string('variant_slug')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->nullable();
            $table->timestamps();


            $table->foreign('quote_number')->references('quote_number')->on('quotes')->onDelete('cascade');
            $table->foreign('product_slug')->references('slug')->on('products')->onDelete('cascade');
            $table->foreign('variant_slug')->references('slug')->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_quote_items');
    }
};

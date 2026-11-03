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
        Schema::create('product_variants', function (Blueprint $table) {

            $table->id();
            $table->string('slug')->unique();

            $table->string('name')->nullable();
            $table->string('name_zh')->nullable();
            $table->string('name_fr')->nullable();

            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            $table->string('sku')->unique();

            $table->decimal('price', 12, 2);
            $table->unsignedBigInteger('colour_id')->nullable();

            // Inventory
            $table->boolean('is_available')->nullable(true);
            $table->boolean('is_active')->default(true);

            $table->timestamps();



            // foreign
            $table->foreign('colour_id')->references('id')->on('colours')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};

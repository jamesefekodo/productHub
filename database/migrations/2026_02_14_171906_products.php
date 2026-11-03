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
        Schema::create('products', function (Blueprint $table) {

            $table->id();

            $table->string('name');
            $table->string('name_zh')->nullable();
            $table->string('name_fr')->nullable();

            $table->string('slug')->unique();

            $table->longText('description')->nullable();
            $table->longText('description_zh')->nullable();
            $table->longText('description_fr')->nullable();

            $table->text('short_description')->nullable();
            $table->text('short_description_zh')->nullable();
            $table->text('short_description_fr')->nullable();


            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();
            $table->foreignId('sub_category_id')
                ->constrained('sub_categories')
                ->cascadeOnDelete();

            $table->foreignId('brand_id')
                ->nullable()
                ->constrained('brands')
                ->nullOnDelete();

            $table->decimal('price', 12, 2)->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);

            $table->text('meta_title')->nullable();
            $table->text('meta_title_zh')->nullable();
            $table->text('meta_title_fr')->nullable();

            $table->text('meta_keywords')->nullable();
            $table->text('meta_keywords_zh')->nullable();
            $table->text('meta_keywords_fr')->nullable();

            $table->text('meta_description')->nullable();
            $table->text('meta_description_zh')->nullable();
            $table->text('meta_description_fr')->nullable();

            $table->string('sku')->nullable();
            $table->boolean('is_available')->default(true);

            $table->unsignedBigInteger('colour_id')->nullable();


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
        Schema::dropIfExists('products');
    }
};

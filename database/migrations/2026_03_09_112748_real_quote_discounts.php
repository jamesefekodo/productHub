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
        Schema::create('real_quote_discounts', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number');
            $table->unsignedBigInteger('discount_id');
            $table->timestamps();


            $table->foreign('quote_number')->references('quote_number')->on('quotes')->onDelete('cascade');
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('real_quote_discounts');
    }
};

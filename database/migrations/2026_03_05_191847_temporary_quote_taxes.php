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
        Schema::create('temporary_quote_taxes', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number');
            $table->unsignedBigInteger('tax_id');
            $table->timestamps();


            $table->foreign('quote_number')->references('quote_number')->on('temporary_quotes')->onDelete('cascade');
            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_quote_taxes');
    }
};

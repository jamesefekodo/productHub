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
        Schema::create(
            'user_addresses',
            function (Blueprint $table) {
                $table->id();
                $table->string('username'); // Foreign
                $table->unsignedBigInteger('country'); // Foreign
                $table->unsignedBigInteger('state'); // Foreign
                $table->unsignedBigInteger('city'); // Foreign
                $table->text('more_details');
                $table->boolean('is_primary')->default(false);
                $table->timestamps();



                $table->foreign('username')->references('username')->on('users')->onDelete('cascade');
                $table->foreign('country')->references('id')->on('countries')->onDelete('cascade');
                $table->foreign('state')->references('id')->on('states')->onDelete('cascade');
                $table->foreign('city')->references('id')->on('cities')->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};

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
            'paystack_api_settings',
            function (Blueprint $table) {
                $table->id();
                $table->string('payment_method_slug')->unique(); //foreign
                $table->string('secret_key')->unique()->nullable();
                $table->string('public_key')->unique()->nullable();
                $table->timestamps();


                $table->foreign('payment_method_slug')
                    ->references('slug')
                    ->on('payment_methods')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paystack_api_settings');
    }
};

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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('request_slug')->nullable(); // Foreign
            $table->string('quote_number')->unique();
            $table->string('customer')->nullable(); // foreign
            $table->string('customer_name')->nullable(); // for non regisered user
            $table->string('customer_email')->nullable(); // for non regisered user
            $table->string('customer_phone')->nullable(); // for non regisered user
            $table->string('customer_address')->nullable(); // for non regisered user
            $table->text('address')->nullable();
            $table->decimal('subtotal', 12, 2)->nullable();
            $table->decimal('total_tax', 12, 2)->nullable();
            $table->decimal('total_discount', 12, 2)->nullable();
            $table->decimal('grand_total', 12, 2)->nullable();
            $table->string('currency')->nullable();
            $table->boolean('is_tax_inclusive')->default(false);
            $table->dateTime('issue_date')->nullable();
            $table->dateTime('valid_until')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('viewed_at')->nullable();
            $table->dateTime('accepted_at')->nullable();
            $table->dateTime('declined_at')->nullable();
            $table->dateTime('converted_at')->nullable();
            $table->longText('internal_note')->nullable();
            $table->longText('customer_note')->nullable();
            $table->longText('terms_and_conditions')->nullable();
            $table->string('created_by')->nullable(); // foreign
            $table->string('last_update_by')->nullable(); // foreign
            $table->boolean('locked')->default(false);
            $table->string('status')->default('draft'); // draft, sent, viewed, accepted, declined, expired, converted_to_order



            // foreign fields
            $table->foreign('customer')->references('username')->on('users')->onDelete('cascade');
            $table->foreign('request_slug')->references('slug')->on('quote_requests')->onDelete('cascade');
            $table->foreign('created_by')->references('username')->on('users')->onDelete('cascade');
            $table->foreign('last_update_by')->references('username')->on('users')->onDelete('cascade');




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};

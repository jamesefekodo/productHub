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
        Schema::create('user_login_records', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('session_id');
            $table->dateTime('logged_in_at');
            $table->dateTime('mfa_verified_at')->nullable();
            $table->dateTime('logged_out_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('device_type')->nullable();
            $table->timestamps();


            // Foreign Field
            $table->foreign('username')->references('username')->on('users')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_login_records');
    }
};

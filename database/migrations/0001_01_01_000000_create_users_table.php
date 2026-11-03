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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();


            $table->string('account_type')->default(2); // 0 - root, 1-admin , 2- staff, 3-customer
            $table->string('account_status')->default(1); // 0 - disabled , 1- active
            $table->string('email1')->unique();
            $table->string('email2')->unique()->nullable();
            $table->timestamp('email1_verified_at')->nullable();
            $table->timestamp('email2_verified_at')->nullable();
            $table->string('phone1')->unique()->nullable();
            $table->string('phone2')->unique()->nullable();
            $table->string('password');
            $table->string('photo')->nullable();
            $table->boolean('require_login_mfa')->default(0);
            $table->string('email1_verification')->nullable()->default('not_verified');
            $table->string('email2_verification')->nullable()->default('not_verified');
            $table->string('email1_verification_code')->nullable();
            $table->string('email2_verification_code')->nullable();
            $table->timestamp('email1_verification_code_expires_at')->nullable();
            $table->timestamp('email2_verification_code_expires_at')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('nationality')->nullable();
            $table->string('preferred_language')->default('en');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

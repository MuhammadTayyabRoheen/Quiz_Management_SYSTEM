<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'manager', 'student', 'supervisor']); // Restore the role column
            $table->string('status')->default('pending'); // Status field with default value 'pending'
            $table->string('reset_token')->nullable(); // Reset token for password reset functionality
            $table->rememberToken(); // For 'remember me' functionality
            $table->timestamps();
            $table->softDeletes();  // Soft deletes
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};




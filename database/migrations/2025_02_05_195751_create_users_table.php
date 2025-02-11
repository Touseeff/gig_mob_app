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
        // Create the 'users' table
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary key 'id'
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null'); // Foreign key for role_id, nullable with constraint
            $table->string('first_name', 100)->nullable(); 
            $table->string('middle_name', 100)->nullable(); 
            $table->string('last_name', 100)->nullable(); 
            $table->string('email', 100)->unique()->nullable(); 
            $table->string('password', 100); 
            $table->string('profile_image', 255)->nullable(); 
            $table->string('schedule_type', 100)->nullable(); 
            $table->string('facebook_auth_id', 100)->nullable(); 
            $table->string('google_auth_id', 100)->nullable();
            $table->string('apple_auth_id', 100)->nullable(); 
            $table->string('remember_token', 100)->nullable(); 
            $table->string('forgot_password_token', 300)->nullable(); 
            $table->string('reset_password_token', 300)->nullable(); 
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active'); 

            
            // $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
        });

        
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // Primary key is email
            $table->string('token');
            $table->timestamp('created_at')->nullable(); // Created At Timestamp
        });

        // Create the 'sessions' table
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

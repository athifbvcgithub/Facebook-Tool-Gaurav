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
        Schema::create('facebook_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('facebook_user_id')->unique();
            $table->string('name');
            $table->string('email')->nullable();
            $table->text('profile_picture')->nullable();
            $table->text('access_token');
            $table->timestamp('token_expires_at')->nullable();
            $table->text('refresh_token')->nullable();
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            $table->timestamps();
            
            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_accounts');
    }
};
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
        Schema::create('facebook_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('facebook_account_id');
            $table->string('page_id')->unique();
            $table->string('page_name');
            $table->text('page_access_token');
            $table->string('category')->nullable();
            $table->string('instagram_business_account')->nullable();
            $table->integer('followers_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->boolean('is_published')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->json('permissions')->nullable(); // Store page permissions
	    $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Foreign key
            $table->foreign('facebook_account_id')
                  ->references('id')
                  ->on('facebook_accounts')
                  ->onDelete('cascade');
            
            // Indexes
            $table->index('facebook_account_id');
            $table->index('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_pages');
    }
};
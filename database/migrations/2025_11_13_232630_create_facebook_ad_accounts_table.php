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
        Schema::create('facebook_ad_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('facebook_account_id');
            $table->string('ad_account_id')->unique();
            $table->string('account_name');
            $table->string('currency', 10)->nullable();
            $table->string('timezone_name', 100)->nullable();
            $table->integer('account_status')->default(1);
            $table->string('business_id')->nullable();
            $table->string('business_name')->nullable();
            $table->decimal('spend_cap', 15, 2)->nullable();
            $table->decimal('balance', 15, 2)->default(0);
            $table->decimal('amount_spent', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('capabilities')->nullable(); // Store account capabilities
            $table->timestamps();
            
            // Foreign key
            $table->foreign('facebook_account_id')
                  ->references('id')
                  ->on('facebook_accounts')
                  ->onDelete('cascade');
            
            // Indexes
            $table->index('facebook_account_id');
            $table->index('is_active');
            $table->index('account_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_ad_accounts');
    }
};
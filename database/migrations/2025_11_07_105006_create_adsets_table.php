<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adsets', function (Blueprint $table) {
            $table->id();
            $table->string('adset_id', 100)->nullable()->unique()->comment('Facebook Adset ID');
            $table->unsignedBigInteger('campaign_id')->index()->comment('Local campaign ID');
            $table->string('facebook_campaign_id', 100)->nullable()->index()->comment('Facebook Campaign ID');
            
            $table->string('name', 255);
            $table->integer('daily_budget')->comment('Budget in cents');
            $table->integer('cost_per_result_goal')->nullable()->comment('Cost goal in cents');
            
            // Optimization & Billing
            $table->string('performance_goal', 255)->nullable();
            $table->string('billing_event', 255)->nullable();
            $table->string('bid_strategy', 255)->nullable();
            
            // Targeting
            $table->string('page_id', 100)->comment('Facebook Page ID');
            $table->string('pixel_id', 100)->nullable()->comment('Facebook Pixel ID');
            $table->string('country_preset', 10)->nullable()->comment('Country code: IN, US, etc');
            $table->json('targeting')->nullable()->comment('Advanced targeting JSON');
            
            // Schedule
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->string('timezone', 50)->nullable()->default('Asia/Kolkata');
            
            $table->enum('status', ['ACTIVE', 'PAUSED'])->default('PAUSED')->index();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adsets');
    }
};
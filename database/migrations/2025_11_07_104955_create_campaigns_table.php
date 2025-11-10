<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('campaign_id', 100)->nullable()->unique()->comment('Facebook Campaign ID');
            $table->string('provider', 50)->default('facebook')->comment('facebook, google, etc');
            $table->string('ad_account_id', 100)->index()->comment('Facebook Ad Account ID');
            $table->unsignedBigInteger('preset_id')->nullable()->comment('Preset configuration ID');
            
            $table->string('name', 255);
            $table->enum('objective', [
                'OUTCOME_SALES',
                'OUTCOME_TRAFFIC',
                'OUTCOME_ENGAGEMENT',
                'OUTCOME_LEADS',
                'OUTCOME_AWARENESS',
                'OUTCOME_APP_PROMOTION'
            ]);
            $table->json('special_ad_categories')->nullable()->comment('["CREDIT", "HOUSING", "EMPLOYMENT", "SOCIAL_ISSUES"] or []');
            $table->integer('daily_budget')->comment('Budget in cents (e.g., 10000 = ₹100)');
            $table->enum('status', ['ACTIVE', 'PAUSED'])->default('PAUSED')->index();
            $table->enum('buying_type', ['AUCTION', 'RESERVED'])->default('AUCTION');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['provider', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
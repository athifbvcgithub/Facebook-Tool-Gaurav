<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Make all Facebook IDs nullable
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('campaign_id', 100)->nullable()->change();
        });

        Schema::table('adsets', function (Blueprint $table) {
            $table->string('adset_id', 100)->nullable()->change();
            $table->string('facebook_campaign_id', 100)->nullable()->change();
        });

        Schema::table('ads', function (Blueprint $table) {
            $table->string('ad_id', 100)->nullable()->change();
            $table->string('facebook_adset_id', 100)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('campaign_id', 100)->nullable(false)->change();
        });

        Schema::table('adsets', function (Blueprint $table) {
            $table->string('adset_id', 100)->nullable(false)->change();
            $table->string('facebook_campaign_id', 100)->nullable(false)->change();
        });

        Schema::table('ads', function (Blueprint $table) {
            $table->string('ad_id', 100)->nullable(false)->change();
            $table->string('facebook_adset_id', 100)->nullable(false)->change();
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_launchers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('campaign_names');
            $table->string('provider');
            $table->string('query')->nullable();
            $table->string('ad_account');
            $table->string('language');
            $table->string('country');
            $table->enum('status', ['active', 'paused'])->default('paused');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_launchers');
    }
};
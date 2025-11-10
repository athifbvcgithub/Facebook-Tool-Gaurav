<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('ad_id', 100)->nullable()->unique()->comment('Facebook Ad ID');
            $table->unsignedBigInteger('adset_id')->index()->comment('Local adset ID');
            $table->string('facebook_adset_id', 100)->index()->comment('Facebook Adset ID');
            
            $table->string('name', 255);
            $table->enum('format', [
                'single_image',
                'single_video',
                'carousel',
                'collection'
            ])->default('single_image');
            
            // Creative Content
            $table->text('primary_text');
            $table->string('headline', 40);
            $table->string('description', 30)->nullable();
            $table->string('media_path', 500)->nullable()->comment('Storage path for uploaded media');
            $table->string('media_hash', 100)->nullable()->comment('Facebook media hash after upload');
            
            // Call to Action
            $table->enum('cta_button', [
                'learn_more',
                'shop_now',
                'sign_up',
                'download',
                'contact_us',
                'apply_now',
                'book_now',
                'get_quote'
            ]);
            $table->text('website_url');
            $table->string('display_link', 255)->nullable();
            
            // Tracking
            $table->text('url_parameters')->nullable()->comment('UTM parameters');
            $table->enum('pixel_event', [
                'ViewContent',
                'AddToCart',
                'Purchase',
                'Lead',
                'CompleteRegistration'
            ]);
            
            $table->enum('status', ['ACTIVE', 'PAUSED'])->default('PAUSED')->index();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('adset_id')->references('id')->on('adsets')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
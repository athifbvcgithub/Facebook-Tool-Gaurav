// facebook_pixels table
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facebook_pixels', function (Blueprint $table) {
            $table->id();
            $table->string('pixel_id', 100)->unique();
            $table->string('name', 255);
            $table->string('ad_account_id', 100)->index();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facebook_pixels');
    }
};
// ad_accounts table
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_id', 100)->unique()->comment('Facebook Ad Account ID');
            $table->string('provider', 50)->default('facebook');
            $table->string('name', 255);
            $table->string('currency', 10)->default('INR');
            $table->string('timezone', 50)->default('Asia/Kolkata');
            $table->string('access_token', 500)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_accounts');
    }
};
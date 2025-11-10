<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookPixel extends Model
{
    protected $table = 'facebook_pixels';

    protected $fillable = [
        'pixel_id',
        'name',
        'ad_account_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the ad account that owns the pixel
     */
    public function adAccount()
    {
        return $this->belongsTo(AdAccount::class, 'ad_account_id', 'account_id');
    }

    /**
     * Get all adsets using this pixel
     */
    public function adsets()
    {
        return $this->hasMany(Adset::class, 'pixel_id', 'pixel_id');
    }

    /**
     * Scope to get active pixels
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by ad account
     */
    public function scopeByAdAccount($query, $accountId)
    {
        return $query->where('ad_account_id', $accountId);
    }
}
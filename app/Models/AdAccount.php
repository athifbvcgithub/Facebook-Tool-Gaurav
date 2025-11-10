<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdAccount extends Model
{
    protected $table = 'ad_accounts';

    protected $fillable = [
        'account_id',
        'provider',
        'name',
        'currency',
        'timezone',
        'access_token',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all campaigns for this ad account
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'ad_account_id', 'account_id');
    }

    /**
     * Get all pixels for this ad account
     */
    public function pixels()
    {
        return $this->hasMany(FacebookPixel::class, 'ad_account_id', 'account_id');
    }

    /**
     * Scope to get active accounts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by provider
     */
    public function scopeByProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdLauncher extends Model
{
    protected $table = 'ad_launchers';

    protected $fillable = [
        'name',
        'campaign_names',
        'provider',
        'query',
        'ad_account',
        'language',
        'country',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_PAUSED = 'paused';

    /**
     * Scope to get active launchers
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope to get paused launchers
     */
    public function scopePaused($query)
    {
        return $query->where('status', self::STATUS_PAUSED);
    }

    /**
     * Scope by provider
     */
    public function scopeByProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }

    /**
     * Scope by country
     */
    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    /**
     * Check if launcher is active
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}
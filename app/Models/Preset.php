<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Preset extends Model
{
    protected $table = 'presets';

    protected $fillable = [
        'name',
        'configuration',
        'is_active',
    ];

    protected $casts = [
        'configuration' => 'array',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all campaigns using this preset
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    /**
     * Scope to get active presets
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get configuration value
     */
    public function getConfig($key, $default = null)
    {
        return data_get($this->configuration, $key, $default);
    }
}
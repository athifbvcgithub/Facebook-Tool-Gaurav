<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookPage extends Model
{
    protected $table = 'facebook_pages';

    protected $fillable = [
        'page_id',
        'name',
        'access_token',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get all adsets using this page
     */
    public function adsets()
    {
        return $this->hasMany(Adset::class, 'page_id', 'page_id');
    }

    /**
     * Scope to get active pages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;

    protected $table = 'campaigns';

    protected $fillable = [
        'campaign_id',
        'provider',
        'ad_account_id',
        'preset_id',
        'name',
        'objective',
        'special_ad_categories',
        'daily_budget',
        'status',
        'buying_type',
    ];

    protected $casts = [
        'special_ad_categories' => 'array',
        'daily_budget' => 'integer',
        'preset_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Campaign objectives
    const OBJECTIVE_SALES = 'OUTCOME_SALES';
    const OBJECTIVE_TRAFFIC = 'OUTCOME_TRAFFIC';
    const OBJECTIVE_ENGAGEMENT = 'OUTCOME_ENGAGEMENT';
    const OBJECTIVE_LEADS = 'OUTCOME_LEADS';
    const OBJECTIVE_AWARENESS = 'OUTCOME_AWARENESS';
    const OBJECTIVE_APP_PROMOTION = 'OUTCOME_APP_PROMOTION';

    // Campaign statuses
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_PAUSED = 'PAUSED';

    // Buying types
    const BUYING_TYPE_AUCTION = 'AUCTION';
    const BUYING_TYPE_RESERVED = 'RESERVED';

    // Special ad categories
    const SPECIAL_AD_CREDIT = 'CREDIT';
    const SPECIAL_AD_HOUSING = 'HOUSING';
    const SPECIAL_AD_EMPLOYMENT = 'EMPLOYMENT';
    const SPECIAL_AD_SOCIAL_ISSUES = 'SOCIAL_ISSUES';

    /**
     * Get the ad account that owns the campaign
     */
    public function adAccount()
    {
        return $this->belongsTo(AdAccount::class, 'ad_account_id', 'account_id');
    }

    /**
     * Get the preset configuration
     */
    public function preset()
    {
        return $this->belongsTo(Preset::class);
    }

    /**
     * Get all adsets for this campaign
     */
    public function adsets()
    {
        return $this->hasMany(Adset::class, 'campaign_id', 'id');
    }

    /**
     * Scope to get active campaigns
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope to get paused campaigns
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
     * Get formatted daily budget (convert cents to rupees)
     */
    public function getFormattedDailyBudgetAttribute()
    {
        return '₹' . number_format($this->daily_budget / 100, 2);
    }

    /**
     * Check if campaign is active
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Get special ad categories as readable string
     */
    public function getSpecialAdCategoriesStringAttribute()
    {
        if (empty($this->special_ad_categories)) {
            return 'None';
        }
        return implode(', ', $this->special_ad_categories);
    }
}
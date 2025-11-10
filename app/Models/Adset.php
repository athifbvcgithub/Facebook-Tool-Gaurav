<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Adset extends Model
{
    use SoftDeletes;

    protected $table = 'adsets';

    protected $fillable = [
        'adset_id',
        'campaign_id',
        'facebook_campaign_id',
        'name',
        'daily_budget',
        'cost_per_result_goal',
        'performance_goal',
        'billing_event',
        'bid_strategy',
        'page_id',
        'pixel_id',
        'country_preset',
        'targeting',
        'start_time',
        'end_time',
        'timezone',
        'status',
    ];

    protected $casts = [
        'campaign_id' => 'integer',
        'daily_budget' => 'integer',
        'cost_per_result_goal' => 'integer',
        'targeting' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Performance goals
    const PERFORMANCE_GOAL_CONVERSIONS = 'CONVERSIONS';
    const PERFORMANCE_GOAL_LINK_CLICKS = 'LINK_CLICKS';
    const PERFORMANCE_GOAL_IMPRESSIONS = 'IMPRESSIONS';
    const PERFORMANCE_GOAL_REACH = 'REACH';
    const PERFORMANCE_GOAL_LANDING_PAGE_VIEWS = 'LANDING_PAGE_VIEWS';

    // Billing events
    const BILLING_EVENT_IMPRESSIONS = 'IMPRESSIONS';
    const BILLING_EVENT_LINK_CLICKS = 'LINK_CLICKS';
    const BILLING_EVENT_POST_ENGAGEMENT = 'POST_ENGAGEMENT';

    // Bid strategies
    const BID_STRATEGY_LOWEST_COST = 'LOWEST_COST_WITHOUT_CAP';
    const BID_STRATEGY_BID_CAP = 'LOWEST_COST_WITH_BID_CAP';
    const BID_STRATEGY_COST_CAP = 'COST_CAP';

    // Statuses
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_PAUSED = 'PAUSED';

    /**
     * Get the campaign that owns the adset
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the Facebook page
     */
    public function page()
    {
        return $this->belongsTo(FacebookPage::class, 'page_id', 'page_id');
    }

    /**
     * Get the Facebook pixel
     */
    public function pixel()
    {
        return $this->belongsTo(FacebookPixel::class, 'pixel_id', 'pixel_id');
    }

    /**
     * Get all ads for this adset
     */
    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    /**
     * Scope to get active adsets
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope to get paused adsets
     */
    public function scopePaused($query)
    {
        return $query->where('status', self::STATUS_PAUSED);
    }

    /**
     * Scope by campaign
     */
    public function scopeByCampaign($query, $campaignId)
    {
        return $query->where('campaign_id', $campaignId);
    }

    /**
     * Get formatted daily budget (convert cents to rupees)
     */
    public function getFormattedDailyBudgetAttribute()
    {
        return '₹' . number_format($this->daily_budget / 100, 2);
    }

    /**
     * Get formatted cost goal
     */
    public function getFormattedCostGoalAttribute()
    {
        if (!$this->cost_per_result_goal) {
            return 'N/A';
        }
        return '₹' . number_format($this->cost_per_result_goal / 100, 2);
    }

    /**
     * Check if adset is currently running
     */
    public function isRunning()
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        $now = now();
        
        if ($this->start_time && $this->end_time) {
            return $now->between($this->start_time, $this->end_time);
        }
        
        return true;
    }

    /**
     * Check if adset is active
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}
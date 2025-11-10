<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ad extends Model
{
    use SoftDeletes;

    protected $table = 'ads';

    protected $fillable = [
        'ad_id',
        'adset_id',
        'facebook_adset_id',
        'name',
        'format',
        'primary_text',
        'headline',
        'description',
        'media_path',
        'media_hash',
        'cta_button',
        'website_url',
        'display_link',
        'url_parameters',
        'pixel_event',
        'status',
    ];

    protected $casts = [
        'adset_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Ad formats
    const FORMAT_SINGLE_IMAGE = 'single_image';
    const FORMAT_SINGLE_VIDEO = 'single_video';
    const FORMAT_CAROUSEL = 'carousel';
    const FORMAT_COLLECTION = 'collection';

    // CTA buttons
    const CTA_LEARN_MORE = 'learn_more';
    const CTA_SHOP_NOW = 'shop_now';
    const CTA_SIGN_UP = 'sign_up';
    const CTA_DOWNLOAD = 'download';
    const CTA_CONTACT_US = 'contact_us';
    const CTA_APPLY_NOW = 'apply_now';
    const CTA_BOOK_NOW = 'book_now';
    const CTA_GET_QUOTE = 'get_quote';

    // Pixel events
    const PIXEL_EVENT_VIEW_CONTENT = 'ViewContent';
    const PIXEL_EVENT_ADD_TO_CART = 'AddToCart';
    const PIXEL_EVENT_PURCHASE = 'Purchase';
    const PIXEL_EVENT_LEAD = 'Lead';
    const PIXEL_EVENT_COMPLETE_REGISTRATION = 'CompleteRegistration';

    // Statuses
    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_PAUSED = 'PAUSED';

    /**
     * Get the adset that owns the ad
     */
    public function adset()
    {
        return $this->belongsTo(Adset::class);
    }

    /**
     * Get the campaign through adset
     */
    public function campaign()
    {
        return $this->hasOneThrough(Campaign::class, Adset::class, 'id', 'id', 'adset_id', 'campaign_id');
    }

    /**
     * Scope to get active ads
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope to get paused ads
     */
    public function scopePaused($query)
    {
        return $query->where('status', self::STATUS_PAUSED);
    }

    /**
     * Scope by adset
     */
    public function scopeByAdset($query, $adsetId)
    {
        return $query->where('adset_id', $adsetId);
    }

    /**
     * Scope by format
     */
    public function scopeByFormat($query, $format)
    {
        return $query->where('format', $format);
    }

    /**
     * Check if ad is active
     */
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Get full website URL with parameters
     */
    public function getFullUrlAttribute()
    {
        $url = $this->website_url;
        
        if ($this->url_parameters) {
            $separator = strpos($url, '?') !== false ? '&' : '?';
            $url .= $separator . $this->url_parameters;
        }
        
        return $url;
    }

    /**
     * Get CTA button label
     */
    public function getCtaLabelAttribute()
    {
        $labels = [
            self::CTA_LEARN_MORE => 'Learn More',
            self::CTA_SHOP_NOW => 'Shop Now',
            self::CTA_SIGN_UP => 'Sign Up',
            self::CTA_DOWNLOAD => 'Download',
            self::CTA_CONTACT_US => 'Contact Us',
            self::CTA_APPLY_NOW => 'Apply Now',
            self::CTA_BOOK_NOW => 'Book Now',
            self::CTA_GET_QUOTE => 'Get Quote',
        ];

        return $labels[$this->cta_button] ?? $this->cta_button;
    }

    /**
     * Check if ad has media uploaded
     */
    public function hasMedia()
    {
        return !empty($this->media_path);
    }

    /**
     * Check if media is synced with Facebook
     */
    public function isMediaSynced()
    {
        return !empty($this->media_hash);
    }
}
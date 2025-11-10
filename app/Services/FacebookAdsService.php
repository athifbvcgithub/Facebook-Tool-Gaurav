<?php

namespace App\Services;

use FacebookAds\Api;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Ad;
use FacebookAds\Object\AdCreative;
use FacebookAds\Object\AdImage;
use FacebookAds\Object\AdVideo;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Fields\AdFields;
use FacebookAds\Object\Fields\AdCreativeFields;
use FacebookAds\Exception\Exception as FacebookException;
use Exception;
use Illuminate\Support\Facades\Log;

class FacebookAdsService
{
    protected $api;
    protected $adAccountId;

    public function __construct()
    {
        // Initialize Facebook API
        Api::init(
            config('services.facebook.app_id'),
            config('services.facebook.app_secret'),
            config('services.facebook.access_token')
        );
        
        $this->api = Api::instance();
    }

    /**
     * Set Ad Account ID
     */
    public function setAdAccount($adAccountId)
    {
        // Ensure account ID has 'act_' prefix
        $this->adAccountId = str_starts_with($adAccountId, 'act_') 
            ? $adAccountId 
            : 'act_' . $adAccountId;
        
        return $this;
    }

    /**
     * Create Campaign on Facebook
     */
    public function createCampaign($data)
    {
        try {
            $account = new AdAccount($this->adAccountId);
            
            // Build campaign data
            $campaignData = [
                CampaignFields::NAME => $data['name'],
                CampaignFields::OBJECTIVE => $this->getObjective($data['objective']),
                CampaignFields::STATUS => 'PAUSED',
                CampaignFields::BUYING_TYPE => 'AUCTION',
                CampaignFields::IS_ADSET_BUDGET_SHARING_ENABLED => false,
            ];

            // Special ad categories
            if (!empty($data['special_ad_categories']) && is_array($data['special_ad_categories'])) {
                $campaignData[CampaignFields::SPECIAL_AD_CATEGORIES] = $data['special_ad_categories'];
            } else {
                $campaignData[CampaignFields::SPECIAL_AD_CATEGORIES] = [];
            }

            // CRITICAL FIX: DON'T set daily_budget at campaign level
            // Budget will be set at AdSet level instead
            // if (isset($data['daily_budget']) && $data['daily_budget'] > 0) {
            //     $campaignData[CampaignFields::DAILY_BUDGET] = (int) $data['daily_budget'];
            // }

            Log::info('=== Creating Facebook Campaign (AdSet-Level Budget) ===', [
                'ad_account' => $this->adAccountId,
                'campaign_data' => $campaignData,
            ]);

            Log::info('=== Facebook Campaign API REQUEST BODY === ' . json_encode($campaignData, JSON_PRETTY_PRINT));


            $campaign = $account->createCampaign([], $campaignData);
            
            Log::info('=== Facebook Campaign Created Successfully ===', [
                'campaign_id' => $campaign->id
            ]);

            return [
                'success' => true,
                'campaign_id' => $campaign->id,
                'data' => $campaign->exportAllData()
            ];

        } catch (FacebookException $e) {
            $errorInfo = [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ];

            Log::error('=== Facebook Campaign Creation Failed ===', [
                'ad_account' => $this->adAccountId,
                'error_info' => $errorInfo,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create AdSet on Facebook
     */
    public function createAdSet($campaignId, $data)
    {
        try {
            $account = new AdAccount($this->adAccountId);
            
            $targeting = $this->buildTargeting($data);
            
            Log::info('Built Targeting', $targeting);
            
            // Billing and Optimization
            $billingEvent = $this->getBillingEvent($data['billing_event']);
            $optimizationGoal = $this->getOptimizationGoal($data['performance_goal']);
            
            Log::info('Mapping Billing Event', ['input' => $data['billing_event'], 'output' => $billingEvent]);
            Log::info('Mapping Optimization Goal', ['input' => $data['performance_goal'], 'output' => $optimizationGoal]);
            
            // Build AdSet Data - MINIMAL REQUIRED FIELDS ONLY
            $adsetData = [
                AdSetFields::NAME => $data['name'],
                AdSetFields::CAMPAIGN_ID => $campaignId,
                AdSetFields::STATUS => 'PAUSED',
                AdSetFields::DAILY_BUDGET => (int) $data['daily_budget'],
                AdSetFields::BILLING_EVENT => $billingEvent,
                AdSetFields::OPTIMIZATION_GOAL => $optimizationGoal,
                AdSetFields::TARGETING => $targeting,
            ];
            
            // CRITICAL FIX: Always set bid_amount (like Postman)
            // If user provides cost goal, use that, otherwise use minimum value 1
            /* if (isset($data['cost_per_result_goal']) && $data['cost_per_result_goal'] > 0) {
                $adsetData[AdSetFields::BID_AMOUNT] = (int) $data['cost_per_result_goal'];
            } else {
                $adsetData[AdSetFields::BID_AMOUNT] = 1; // Minimum bid amount (1 cent)
            } */

            // IMPORTANT: Only add bid_strategy and bid_amount if cost goal is provided
            if (isset($data['cost_per_result_goal']) && $data['cost_per_result_goal'] > 0) {
                $adsetData[AdSetFields::BID_STRATEGY] = 'COST_CAP';
                $adsetData[AdSetFields::BID_AMOUNT] = (int) $data['cost_per_result_goal'];
                Log::info('Adding Cost Control', [
                    'bid_strategy' => 'COST_CAP',
                    'bid_amount' => $adsetData[AdSetFields::BID_AMOUNT]
                ]);
            } else {
                $adsetData[AdSetFields::BID_AMOUNT] = 1; // Minimum bid amount (1 cent)
                Log::info('No cost control - using automatic bidding');
                // DON'T ADD bid_strategy field at all
            }

            // Start Time
            if (isset($data['start_time'])) {
                if ($data['start_time'] instanceof \Carbon\Carbon) {
                    $startTime = $data['start_time']->toIso8601String();
                } else {
                    $startTime = \Carbon\Carbon::parse($data['start_time'])->toIso8601String();
                }
            } else {
                $startTime = now()->addMinutes(5)->toIso8601String();
            }
            $adsetData[AdSetFields::START_TIME] = $startTime;

            

            // Promoted Object - Only for conversion objectives
            if (in_array($optimizationGoal, ['OFFSITE_CONVERSIONS', 'LANDING_PAGE_VIEWS'])) {
                if (isset($data['pixel_id']) && !empty($data['pixel_id'])) {
                    $adsetData[AdSetFields::PROMOTED_OBJECT] = [
                        'pixel_id' => $data['pixel_id'],
                        'custom_event_type' => 'PURCHASE'
                    ];
                }
            }

            Log::info('=== Creating Facebook AdSet ===', [
                'campaign_id' => $campaignId,
                'adset_data' => $adsetData,
            ]);

            Log::info('=== Facebook AdSet API REQUEST BODY === ' . json_encode($adsetData, JSON_PRETTY_PRINT));

            
            $adset = $account->createAdSet([], $adsetData);
            
            Log::info('=== Facebook AdSet Created Successfully ===', [
                'adset_id' => $adset->id
            ]);

            return [
                'success' => true,
                'adset_id' => $adset->id,
                'data' => $adset->exportAllData()
            ];

        } catch (FacebookException $e) {
            $errorInfo = [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'subcode' => method_exists($e, 'getErrorSubcode') ? $e->getErrorSubcode() : null,
                'user_message' => method_exists($e, 'getErrorUserMessage') ? $e->getErrorUserMessage() : null,
            ];

            Log::error('=== Facebook AdSet Creation Failed ===', [
                'campaign_id' => $campaignId,
                'error_info' => $errorInfo,
                'sent_data' => $adsetData ?? null,
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_details' => $errorInfo
            ];

        } catch (Exception $e) {
            Log::error('=== AdSet Creation Exception ===', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Upload Media and Create Ad Creative
     */
    public function createAdCreative($data)
    {
        try {
            $account = new AdAccount($this->adAccountId);
            
            // Upload media first
            $mediaHash = null;
            if (isset($data['media_path'])) {
                $mediaUpload = $this->uploadMedia($data['media_path'], $data['media_type'] ?? 'image');
                if (!$mediaUpload['success']) {
                    throw new Exception('Media upload failed: ' . $mediaUpload['error']);
                }
                $mediaHash = $mediaUpload['hash'];
            }

            // Build website URL
            $websiteUrl = $data['website_url'];
            if (isset($data['url_parameters']) && !empty($data['url_parameters'])) {
                $separator = str_contains($websiteUrl, '?') ? '&' : '?';
                $websiteUrl .= $separator . $data['url_parameters'];
            }

            // Build object story spec
            $objectStorySpec = [
                'page_id' => $data['page_id'],
                'link_data' => [
                    'message' => $data['primary_text'],
                    'link' => $websiteUrl,
                    'name' => $data['headline'],
                    'call_to_action' => [
                        'type' => $data['cta_button'],
                        'value' => [
                            'link' => $websiteUrl,
                        ]
                    ]
                ]
            ];

            // Add description if provided
            if (isset($data['description']) && !empty($data['description'])) {
                $objectStorySpec['link_data']['description'] = $data['description'];
            }

            // Add media to link data
            if ($mediaHash) {
                if ($data['media_type'] === 'video') {
                    $objectStorySpec['link_data']['video_id'] = $mediaHash;
                } else {
                    $objectStorySpec['link_data']['image_hash'] = $mediaHash;
                }
            }

            // Create Ad Creative
            $creativeData = [
                AdCreativeFields::NAME => $data['name'] . ' - Creative',
                AdCreativeFields::OBJECT_STORY_SPEC => $objectStorySpec,
            ];

            Log::info('=== Creating Facebook Ad Creative ===', [
                'creative_data' => $creativeData
            ]);

            $creative = $account->createAdCreative([], $creativeData);
            
            Log::info('=== Facebook Ad Creative Created Successfully ===', [
                'creative_id' => $creative->id
            ]);

            return [
                'success' => true,
                'creative_id' => $creative->id,
                'data' => $creative->exportAllData()
            ];

        } catch (FacebookException $e) {
            Log::error('=== Facebook Ad Creative Creation Failed ===', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];

        } catch (Exception $e) {
            Log::error('=== Facebook Ad Creative Creation Failed ===', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Create Ad on Facebook
     */
    public function createAd($adsetId, $creativeId, $data)
    {
        try {
            $account = new AdAccount($this->adAccountId);
            
            $adData = [
                AdFields::NAME => $data['name'],
                AdFields::ADSET_ID => $adsetId,
                AdFields::CREATIVE => ['creative_id' => $creativeId],
                AdFields::STATUS => 'PAUSED',
            ];

            // Add tracking specs if pixel event provided
            /* if (isset($data['pixel_event']) && !empty($data['pixel_event']) && isset($data['pixel_id'])) {
                $adData[AdFields::TRACKING_SPECS] = [
                    [
                        'action.type' => $data['pixel_event'],
                        'fb_pixel' => [$data['pixel_id']]
                    ]
                ];
            } */

            Log::info('=== Creating Facebook Ad ===', [
                'adset_id' => $adsetId,
                'ad_data' => $adData
            ]);

            $ad = $account->createAd([], $adData);
            
            Log::info('=== Facebook Ad Created Successfully ===', [
                'ad_id' => $ad->id
            ]);

            return [
                'success' => true,
                'ad_id' => $ad->id,
                'data' => $ad->exportAllData()
            ];

        } catch (FacebookException $e) {
            Log::error('=== Facebook Ad Creation Failed ===', [
                'adset_id' => $adsetId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];

        } catch (Exception $e) {
            Log::error('=== Facebook Ad Creation Failed ===', [
                'adset_id' => $adsetId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Upload Media (Image or Video)
     */
    protected function uploadMedia($filePath, $type = 'image')
    {
        try {
            $account = new AdAccount($this->adAccountId);
            $fullPath = storage_path('app/public/' . $filePath);

            if (!file_exists($fullPath)) {
                throw new Exception('Media file not found: ' . $fullPath);
            }

            Log::info('=== Uploading Media ===', [
                'type' => $type,
                'path' => $fullPath,
                'file_size' => filesize($fullPath)
            ]);

            if ($type === 'video') {
                $video = $account->createAdVideo([], [
                    'source' => $fullPath,
                ]);
                
                Log::info('=== Video Uploaded Successfully ===', [
                    'video_id' => $video->id,
                    'full_response' => $video->exportAllData()
                ]);

                return [
                    'success' => true,
                    'hash' => $video->id,
                    'type' => 'video'
                ];
            } else {
                // Image upload
                $image = $account->createAdImage([], [
                    'filename' => $fullPath,
                ]);
                
                // ✅ FIX: Get hash from images array
                $imageData = $image->exportAllData();
                $imageHash = null;
                
                // Facebook returns hash in 'images' array with filename as key
                if (isset($imageData['images']) && is_array($imageData['images'])) {
                    // Get the first (and only) image from the array
                    $firstImage = reset($imageData['images']);
                    if (isset($firstImage['hash'])) {
                        $imageHash = $firstImage['hash'];
                    }
                }
                
                // Fallback: try direct hash property
                if (!$imageHash && isset($imageData['hash'])) {
                    $imageHash = $imageData['hash'];
                }
                
                Log::info('=== Image Uploaded Successfully ===', [
                    'image_hash' => $imageHash,
                    'full_response' => $imageData
                ]);

                if (!$imageHash) {
                    throw new Exception('Failed to get image hash from Facebook API response');
                }

                return [
                    'success' => true,
                    'hash' => $imageHash,
                    'type' => 'image'
                ];
            }

        } catch (Exception $e) {
            Log::error('=== Media Upload Failed ===', [
                'error' => $e->getMessage(),
                'file_path' => $fullPath ?? 'unknown'
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    /* protected function uploadMedia($filePath, $type = 'image')
    {
        try {
            $account = new AdAccount($this->adAccountId);
            $fullPath = storage_path('app/public/' . $filePath);

            if (!file_exists($fullPath)) {
                throw new Exception('Media file not found: ' . $fullPath);
            }

            Log::info('=== Uploading Media ===', [
                'type' => $type,
                'path' => $fullPath
            ]);

            if ($type === 'video') {
                $video = $account->createAdVideo([], [
                    'source' => $fullPath,
                ]);
                
                Log::info('=== Video Uploaded Successfully ===', ['video_id' => $video->id]);

                return [
                    'success' => true,
                    'hash' => $video->id,
                    'type' => 'video'
                ];
            } else {
                $image = $account->createAdImage([], [
                    'filename' => $fullPath,
                ]);
                
                Log::info('=== Image Uploaded Successfully ===', ['image_hash' => $image->hash]);

                return [
                    'success' => true,
                    'hash' => $image->hash,
                    'type' => 'image'
                ];
            }

        } catch (Exception $e) {
            Log::error('=== Media Upload Failed ===', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    } */

    /**
     * Build Targeting
     */
    protected function buildTargeting($data)
    {
        $targeting = [];

        // Geo Location - REQUIRED
        if (isset($data['country_preset']) && !empty($data['country_preset'])) {
            $countries = is_array($data['country_preset']) 
                ? $data['country_preset'] 
                : [$data['country_preset']];
            
            $targeting['geo_locations'] = [
                'countries' => $countries
            ];
        } else {
            // Default to India
            $targeting['geo_locations'] = [
                'countries' => ['IN']
            ];
        }

        // Age targeting - REQUIRED for most objectives
        $targeting['age_min'] = 18;
        $targeting['age_max'] = 65;

        Log::info('Built Targeting', $targeting);

        return $targeting;
    }

    /**
     * Helper: Get Campaign Objective
     */
    protected function getObjective($objective)
    {
        $objectives = [
            'OUTCOME_SALES' => 'OUTCOME_SALES',
            'OUTCOME_LEADS' => 'OUTCOME_LEADS',
            'OUTCOME_ENGAGEMENT' => 'OUTCOME_ENGAGEMENT',
            'OUTCOME_TRAFFIC' => 'OUTCOME_TRAFFIC',
            'OUTCOME_AWARENESS' => 'OUTCOME_AWARENESS',
            'OUTCOME_APP_PROMOTION' => 'OUTCOME_APP_PROMOTION',
        ];

        return $objectives[strtoupper($objective)] ?? 'OUTCOME_TRAFFIC';
    }

    /**
     * Helper: Get Billing Event
     */
    protected function getBillingEvent($event)
    {
        $events = [
            'IMPRESSIONS' => 'IMPRESSIONS',
            'LINK_CLICKS' => 'LINK_CLICKS',
            'POST_ENGAGEMENT' => 'POST_ENGAGEMENT',
        ];

        $mapped = $events[strtoupper($event)] ?? 'IMPRESSIONS';
        
        Log::info('Mapping Billing Event', [
            'input' => $event,
            'output' => $mapped
        ]);

        return $mapped;
    }

    /**
     * Helper: Get Optimization Goal
     */
    protected function getOptimizationGoal($goal)
    {
        $goals = [
            'CONVERSIONS' => 'OFFSITE_CONVERSIONS',
            'OFFSITE_CONVERSIONS' => 'OFFSITE_CONVERSIONS',
            'LINK_CLICKS' => 'LINK_CLICKS',
            'IMPRESSIONS' => 'IMPRESSIONS',
            'REACH' => 'REACH',
            'LANDING_PAGE_VIEWS' => 'LANDING_PAGE_VIEWS',
            'POST_ENGAGEMENT' => 'POST_ENGAGEMENT',
            'THRUPLAY' => 'THRUPLAY',
        ];

        $mapped = $goals[strtoupper($goal)] ?? 'LINK_CLICKS';
        
        Log::info('Mapping Optimization Goal', [
            'input' => $goal,
            'output' => $mapped
        ]);

        return $mapped;
    }
}
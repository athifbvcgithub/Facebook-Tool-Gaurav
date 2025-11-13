<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdAccount extends Model  
{
    protected $table = 'facebook_ad_accounts';  // Table name
    
    protected $fillable = [
        'facebook_account_id',
        'ad_account_id',
        'account_name',
        'currency',
        'timezone_name',
        'account_status',
        'business_id',
        'business_name',
        'spend_cap',
        'balance',
        'amount_spent',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'balance' => 'decimal:2',
        'spend_cap' => 'decimal:2',
        'amount_spent' => 'decimal:2'
    ];
    
    // Relationship back to FacebookAccount
    public function facebookAccount()
    {
        return $this->belongsTo(\App\Models\FacebookAccount::class, 'facebook_account_id', 'id');
    }
}
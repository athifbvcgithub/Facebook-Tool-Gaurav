<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FacebookAccount extends Model
{
    use HasFactory;

    protected $table = 'facebook_accounts';

    protected $fillable = [
        'user_id',
        'facebook_user_id',
        'name',
        'email',
        'profile_picture',
        'access_token',
        'token_expires_at',
        'refresh_token',
        'status'
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
    ];

    protected $hidden = [
        'access_token',
        'refresh_token'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // CORRECT RELATIONSHIP - Use AdAccount, not FacebookAdAccount
    public function adAccounts()
    {
        return $this->hasMany(\App\Models\AdAccount::class, 'facebook_account_id', 'id');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(FacebookPage::class);
    }

    // Helper methods
    public function isTokenExpired(): bool
    {
        return $this->token_expires_at && $this->token_expires_at->isPast();
    }

    public function getDecryptedToken(): string
    {
        return decrypt($this->access_token);
    }
}
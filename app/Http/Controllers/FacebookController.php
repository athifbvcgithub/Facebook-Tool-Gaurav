<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FacebookAuthController extends Controller
{
    public function handleCallback(Request $request)
    {
        $code = $request->get('code');

        if (!$code) {
            return "Authorization failed or canceled.";
        }

        // Exchange code for access token
        $response = Http::get('https://graph.facebook.com/v3.2/oauth/access_token', [
            'client_id' => '1373727363265571',
            'redirect_uri' => 'http://103.209.147.85/oauth/facebook',
            'client_secret' => 'YOUR_FACEBOOK_APP_SECRET', // replace this
            'code' => $code,
        ]);

        $data = $response->json();

        if (isset($data['access_token'])) {
            // Save token to DB or session
            return "Facebook connected successfully! Access token: " . $data['access_token'];
        } else {
            return "Failed to fetch access token.";
        }
    }
}

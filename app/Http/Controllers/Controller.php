<?php

namespace App\Http\Controllers;

use App\Models\UserToken;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function generateAccessToken()
    {
        return strtoupper(bin2hex(openssl_random_pseudo_bytes(20)));
    }

    public function generateUserToken(\Illuminate\Http\Request $request, $user)
    {
        $oauth = UserToken::create(
            [
                'user_id' => $user->id,
                'revoked' => 0,
                'access_token' => $this->generateAccessToken(),
                'refresh_key' => $this->generateRefreshKey(),
                'expires_at' => Carbon::now()->addMinute(3600)->toDateTimeString()
            ]
        );

        return $oauth->access_token;

    }

    public function getUserId($access_token){
        $token = UserToken::where('access_token', $access_token)->first();
        return $token->user_id;
    }

    public function generateRefreshKey()
    {
        return strtoupper(Str::random(15));
    }

    public function generatePasswordReset()
    {
        return strtoupper(Str::random(30));
    }

}
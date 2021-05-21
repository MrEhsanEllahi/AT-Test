<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    use HasFactory;

    const ID='id';
    const USER_ID='user_id';
    const REVOKED='revoked';
    const ACCESS_TOKEN='access_token';
    const REFRESH_KEY='refresh_key';
    const EXPIRES_AT='expires_at';

    protected $fillable=[
        UserToken::ID,
        UserToken::USER_ID,
        UserToken::REVOKED,
        UserToken::ACCESS_TOKEN,
        UserToken::REFRESH_KEY,
        UserToken::EXPIRES_AT
    ];

    protected $hidden=[
        UserToken::EXPIRES_AT,
        UserToken::CREATED_AT,
        UserToken::UPDATED_AT
    ];

    public function user()
    {
    	return $this->belongsTo('App\Models\User', 'user_id');
    }
}

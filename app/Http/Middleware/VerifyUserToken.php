<?php

namespace App\Http\Middleware;

use App\Models\UserToken;
use Closure;
use Validator;
use Illuminate\Http\Request;

class VerifyUserToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $rules = [
            'access_token' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                "error" => [
                    'error' => ['Access Restricted Without Access Token.']
                ]
            ], 401);
        }

//        $access_token = $request->access_token;

        $token = UserToken::where('access_token', request('access_token'))->first();

        if (is_null($token)) {
            return response()->json([
                'status' => false,
                "error" => [
                    'error' => ['Invalid Access Token. Please Login Again.']
                ]
            ], 401);
        }

        return $next($request);
    }
}

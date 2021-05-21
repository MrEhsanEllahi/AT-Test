<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Validator;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function loginForToken(Request $request){
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(["status" => false, "error" => $validator->errors()->first()], 400);
        }

        $user = User::where('email', $request->email)->first();

        if(!$user){
            return response()->json([
                "status" => false,
                "error" => "No User found."
            ], 404);
        }

        $check  = Hash::check($request->password, $user->password);

        if(!$check){
            return response()->json([
                "status" => false,
                "error" => "Incorrect Password"
            ], 400);
        }

        $token = $this->generateUserToken($request, $user);

        return response()->json([
            "status" => true,
            "message" => "Logged in successfully",
            "user" => $user->toArray(),
            "access_token" => $token,
        ], 200);
    }

    public function login(Request $request){
        $response = Http::post('https://showitwithmackie.com/spotat/public/api/auth/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return response()->json($response->json(), $response->status());
    }

    public function register(Request $request){
        $response = Http::post('https://showitwithmackie.com/spotat/public/api/auth/register', [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'confirm' => $request->confirm,
            'company_name' => $request->company_name,
            'company_no' => $request->company_no,
        ]);

        return response()->json($response->json(), $response->status());
    }

    public function logout(){
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->withToken(request('bearer_token'))->get('https://showitwithmackie.com/spotat/public/api/auth/logout');
        return response()->json($response->json(), $response->status());
    }

    public function profile(){
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->withToken(request('bearer_token'))->get('https://showitwithmackie.com/spotat/public/api/auth/user');
        return response()->json($response->json(), $response->status());
    }

    public function updateProfile(Request $request){
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->withToken(request('bearer_token'))->post('https://showitwithmackie.com/spotat/public/api/auth/user/edit', [
            'name' => $request->name,
            'bio' => $request->bio,
            'email' => $request->email,
            'password' => $request->password,
            'confirm_password' => $request->confirm_password,
            'location' => $request->location,
        ]);

        return response()->json($response->json(), $response->status());
    }
}

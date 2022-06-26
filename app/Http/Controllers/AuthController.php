<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
    protected $userSelect = [
        // Users
        'users.id',
        'users.name',
        'users.email',
        'users.email_verified_at',
        'users.created_at',
        // UserData
        'user_data.site',
        'user_data.phone',
        'user_data.country',
        'user_data.city',
        'user_data.address',
        'user_data.gender',
        'user_data.avatar',
        // Country
        'countries.name as country_name',
        'countries.region as country_region',
        'countries.subregion as country_subregion',
        'countries.latitude as country_latitude',
        'countries.longitude as country_longitude',
    ];
    
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device' => ['required'],
        ]);
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web')->attempt($credentials, $request->remember)) {
            // Authorized
            // $data = (new UserController)->index($request);
            return new JsonResponse([
                'message' => 'Authenticated',
                'new_token' => $request->user()->createToken('TestToken'),
                'auth' => true
            ], 200);
        }

        // Not Authorized
        return new JsonResponse([
            'message' => 'Unauthenticated',
            'errors' => [
                'email' => ["It seems the email password combination you entered is incorrect. Please try again."]
            ],
            'auth' => false
        ], 401);
    }

    public function logout(Request $request)
    {   
        $user = $request->user();
        if(!$user){
            return new JsonResponse([
                'title' => 'Success',
                'message' => 'User not Found',
            ], 200);
        }
        $tokens = $user->tokens()->where('id', '!=', 1)->count();
        if($tokens){
            $user->tokens()->where('id', '!=', 1)->delete();
        }
        return new JsonResponse([
            'title' => 'Success',
            'message' => 'Logout Succesfully',
        ], 200);

    }

    public function user(Request $request)
    {
        // Authorized
        $user = $request->user();
    
        $uq = User::query();
        // Where
        $uq->where('users.id', $user->id);
        // Selects
        $uq->select($this->userSelect);
        // Join
        $uq->join('user_data', 'users.id', '=', 'user_data.user_id');
        $uq->leftJoin('countries', 'user_data.country', '=', 'countries.iso2');
        $user = $uq->first();

        // Token
        $bearer = $request->header('Authorization');
        if($bearer){
            $token = str_replace('Bearer ', '', $bearer);
        } else {
            $token = $user->createToken($request->device)->plainTextToken;
        }
        
        return new JsonResponse([
            'message' => 'Authenticated',
            'auth' => true,
            'token' => $token,
            'user' => $user,
        ], 200);
    }

    // Passwords

    public function setNewPassword(Request $request)
    {
        
        $user = $request->user();

        $credentials = $request->validate([
            'old_password' => ['required'],
            'password' => 'required|min:8|confirmed',
        ]);

       if (Auth::check()) {
        if (Auth::guard('web')->attempt(['email' => $user->email, 'password' => $request->old_password])) {
            // Authorized
            $new_password = Hash::make($request->password);

            $userPass = User::where('id', $user->id)->first();
            $userPass->forceFill([
                'password' => $new_password,
            ]);
            $userPass->save();
            return new JsonResponse([
                'title' => 'Success',
                'message' => 'Password Change Succesfully',
                'auth' => true
            ], 200);
        }
        return new JsonResponse([
            'message' => 'Unauthenticated',
            'errors' => [
                'password' => ["The old password you entered is incorrect. Please try again."]
            ],
            'auth' => false
        ], 401);
        
       }
    }

}

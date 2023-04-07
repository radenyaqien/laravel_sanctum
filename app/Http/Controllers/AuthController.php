<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResponse;


    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return $this->error(null, 'Credentials does not match', 401);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('Api Token ' . $user->name);
        return $this->success([
            'user' => $user,
            'token' => $token->plainTextToken
        ]);
    }

    public function register(StoreUserRequest $request)
    {
        $request->validated($request->all());

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('Api Token ' . $user->name)->plainTextToken
        ]);
    }

    public function logout()
    {

        Auth::user()->currentAccessToken()->delete(); 

        return $this->success(null,"You are successfully logged out");
    }
}

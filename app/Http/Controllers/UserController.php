<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request): UserResource
    {
        $data  = $request->validated();
        if (User::where("username", $data["username"])->count() == 1) {
            // User already exists
        }

        $user = new User($data);
        $user->password = Hash::make($data["password"]);
        $user->save();

        return new UserResource($user);
    }
}

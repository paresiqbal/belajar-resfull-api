<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; // Import the Str class

class UserController extends Controller
{
    // register a new user
    public function register(UserRegisterRequest $request): UserResource
    {
        $data  = $request->validated();
        if (User::where("username", $data["username"])->count() == 1) {
            throw new HttpResponseException(response([
                "errors" => [
                    "username" => [
                        "The username has already been taken.",
                    ],
                ],
            ], 400));
        }

        $user = new User($data);
        $user->password = Hash::make($data["password"]);
        $user->save();

        return new UserResource($user);
    }

    public function login(UserLoginRequest $request): UserResource
    {
        $data =  $request->validated();
        $user = User::where("username", $data["username"])->first();
        if (!$user || Hash::check($data["password"], $user->password) == false) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "Username or Password wrong.",
                    ],
                ],
            ], 401));
        }
        $user->token = Str::uuid()->toString();
        $user->save();

        return new UserResource($user);
    }

    public function get(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}

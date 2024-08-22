<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;

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
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (Hash::check($request->password, $user->password)) {
            if (empty($user->state_code)) {
                $user->state_code = Str::random(40);

                $user->save();
            }

            $this->addUserMetaData($user);

            return UserResource::make($user);
        }

        return response()->json([], Response::HTTP_UNAUTHORIZED);
    }

    public function me()
    {
        $user = request()->user();

        $this->addUserMetaData($user);

        return UserResource::make($user);
    }

    public function saveAccessToken(Request $request)
    {
        $user = request()->user();
        $user->access_token = $request->access_token;
        $user->save();

        $this->addUserMetaData($user);

        return UserResource::make($user);
    }

    private function addUserMetaData(User &$user): void
    {
        $user->callback_url = URL::to('/home');
        $user->base_url = URL::to('');
        $user->oauth_enabled = $user->clients && $user->clients->count() ? true : false;
        $user->secret = $user->oauth_enabled ? $user->clients->first()->secret : null;
    }
}

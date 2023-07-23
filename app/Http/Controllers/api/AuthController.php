<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:3,1')->only('login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error('validator.fails', $validator->errors());
        }

        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'active' => 1
        ])) {
            $user = Auth::user();
            $response['token'] =  $user->createToken('EXAMPLE')->accessToken;
            return $this->ok($response);
        } else {
            return $this->error("Unauthorized", [], 401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password'  =>  'required|min:6|confirmed',
            'password_confirmation'  =>  'required|min:6',
        ]);

        if ($validator->fails()) {
            return $this->error('validator.fails', $validator->errors());
        }

        $data = $validator->validated();
        $data['password'] = Hash::make($request->password);
        $user = User::create($data);

        return $this->ok($user);
    }

    public function me()
    {
        $user = Auth::user();
        return $this->ok($user);
    }

    # --------------------------------- upload image -----------------------------------

    function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return $this->error('validator.fails', $validator->errors());
        }

        $user = $request->user();

        $user->addFile($request);
        $user->save();

        return $this->ok($user);
    }
}

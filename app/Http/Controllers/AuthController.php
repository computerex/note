<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use \App\User;
class AuthController extends Controller
{
    public function __construct()
    {
    }

    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required',
        ]);

        try {
          $creds = $request->only('email', 'password');
           if (! $token = JWTAuth::attempt($creds)) {
                return response()->json(['user_not_found'], 404);
           }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 500);

        }
        $_SESSION['user'] = $request->input('email');
        return \App\User::where('email', $request->input('email'))->first();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function __construct()
    {

    }
    public function register(Request $request){
        $payload =[
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password)
        ];

        if(!$request->name){
            return response()->json([
                'message' => 'Name required!',
            ], 400);
        }else if(!$request->email){
            return response()->json([
                'message' => 'Email required!',
            ], 400);
        }else if(!$request->password){
            return response()->json([
                'message' => 'Password required!',
            ], 400);
        }

        $result = User::create($payload);

        $jwtPayload = [
            'iat' => 1356999524,
            'nbf' => 1357000000,
            'uid' => $result->id,
        ];

        $jwt = JWT::encode($jwtPayload, env('JWT_SECRET'), 'HS256');

        if($result){
            return response()->json([
                'message'       => 'User successfully created',
                'access_token'  => $jwt
            ], 201);
        }else{
            return response()->json([
                'message' => 'Failed'
            ], 400);
        }

    }

    public function login(Request $request){
		$payload = [
			'email' => $request->email,
			'password' => $request->password
		];
		$validated = Auth::attempt($payload);

		if($validated){
			return response()->json([
				'message' => 'Authorized',
				'access_token' => $this->tokenGenerator(Auth::id()),
			], 200)->cookie('access_token', $this->tokenGenerator(Auth::id()), 72);
		}

		return response()->json([
			'message' => 'Unauthorized'
		], 401);
    }

    public function me(Request $request){
        $extractedToken = explode(' ',$request->header('Authorization'))[1];
        $decoded = JWT::decode($extractedToken, new Key(env('JWT_SECRET'), 'HS256'));
        $response = User::where('id', $decoded->uid)->first();
        if($response){
            return response()->json([
                'message' => 'Authenticated',
                'data' => $response
            ], 200);
        }
    }

	public function tokenGenerator($uid){
		$payload = [
			'iat' => 1356999524,
			'nbf' => 1357000000,
			'uid' => $uid
		];
		$jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');
		return $jwt;
	}
}

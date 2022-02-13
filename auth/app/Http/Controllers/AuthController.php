<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Validator;

//models
use App\Models\User;

class AuthController extends Controller
{

    public function __construct() {
        $this->middleware('auth', ['except' => ['login', 'register']]);
    }

    protected function jwt(User $user) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60*60 // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, config('jwt.secret'), 'HS256');
    }


    protected function jwtLogout(User $user) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() // Expiration time now
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, config('jwt.secret'), 'HS256');
    }

    /**
     * Get a JWT via given credentials.
    */
    public function login(Request $request){
    	$req = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:5',
        ]);

        if ($req->fails()) {
            return response()->json($req->errors(), 422);
        }

        if (!auth()->attempt($req->validated())) {
            return response()->json(['Auth error' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request->email)->first();
        return response()->json([
            'token' => $this->jwt($user)
        ], 200);
    }

    /**
     * Sign up.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $req = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if($req->fails()){
            return response()->json($req->errors(), 400);
        }

        User::create(array_merge(
            $req->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return $this->login($request);
    }


    /**
     * Sign out
    */
    public function signout(Request $request) {
        $decoded = JWT::decode($request->bearerToken(), new Key(config('jwt.secret'), 'HS256'));
        $user = User::find($decoded->sub);
        $this->jwtLogout($user);
        return response()->json(['message' => 'User loged out']);
    }

    /**
     * User
    */
    public function user(Request $request) {
        $decoded = JWT::decode($request->bearerToken(), new Key(config('jwt.secret'), 'HS256'));
        $user = User::find($decoded->sub);
        return response()->json($user);
    }
}

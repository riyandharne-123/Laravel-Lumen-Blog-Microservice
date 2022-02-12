<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $http;
    protected $auth_url;

    public function __construct()
    {
        $this->http = new Http();
        $this->auth_url = env('AUTH_SERVICE_URL');
    }

    public function register(Request $request)
    {
        $data = $this->http::post($this->auth_url . '/register', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password
        ]);

        if($data->clientError()) {
            return response($data->json(), 401);
        }

        return response($data->json(), 200);
    }

    public function login(Request $request)
    {
        $data = $this->http::post($this->auth_url . '/login', [
            'email' => $request->email,
            'password' => $request->password
        ]);

        if($data->clientError()) {
            return response($data->json(), 401);
        }

        return response($data->json(), 200);
    }

    public function user(Request $request)
    {
        $data = $this->http::withToken($request->bearerToken())->get($this->auth_url . '/user');

        if ($data->clientError() || $data->serverError()) {
            return response($data->throw(), 401);
        }

        return response($data->json(), 200);
    }

    public function logout(Request $request)
    {
        $data = $this->http::withToken($request->bearerToken())->post($this->auth_url . '/signout');

        if ($data->clientError() || $data->serverError()) {
            return response($data->throw(), 401);
        }

        return response($data->json(), 200);
    }
}

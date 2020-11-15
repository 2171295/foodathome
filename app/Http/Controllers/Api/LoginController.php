<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $http = new \GuzzleHttp\Client;
        $form_params = [
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_ID', 2),
            'client_secret' => env('PASSPORT_SECRET'),
            'username' => $request->email,
            'password' => $request->password,
            'scope' => ''
        ];
        $response = $http->post(env('PASSPORT_URL').'/oauth/token', [
            'form_params' => $form_params,
            'exceptions' => true,
        ]);
        $errorCode = $response->getStatusCode();
        if ($errorCode == '200') {
            return json_decode((string)$response->getBody(), true);
        } else {
            return response()->json(
                ['msg' => 'User credentials are invalid'], $errorCode);
        }
    }

    public function logout()
    {
        \Auth::guard('api')->user()->token()->revoke();
        \Auth::guard('api')->user()->token()->delete();
        return response()->json(['msg'=>'Token revoked'], 200);
    }
}

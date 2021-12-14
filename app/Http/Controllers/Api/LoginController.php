<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $errorCode = 0;
        $user = User::withTrashed()->where('email',$request->email)->get();
        if($user[0]->blocked && $user[0]->deleted_at === null){
            $errorCode = '301';
        }
        if($user[0]->blocked && $user[0]->deleted_at!== null){
            $errorCode = '302';
        }

        if ($errorCode === 0) {
            $http = new \GuzzleHttp\Client;
            $form_params = [
                'grant_type' => 'password',
                'client_id' => env('PASSPORT_ID', 2),
                'client_secret' => env('PASSPORT_SECRET'),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => ''
            ];
            $response = $http->post(env('PASSPORT_URL') . '/oauth/token', [
                'form_params' => $form_params,
                'exceptions' => true,
            ]);
            $errorCode = $response->getStatusCode();
        }

        switch ($errorCode){
            case '200':
                Log::channel('cyber')->info($request);
                return json_decode((string)$response->getBody(), true);
                break;
            case '301':
                Log::channel('cyber')->warning("BLOCKED USER");
                Log::channel('cyber')->warning($request);
                return response()->json(
                    ['msg' => 'Your account was blocked.', 'errorCode' => $errorCode]);
            break;
            case '302':
                Log::channel('cyber')->warning("DELETED USER");
                Log::channel('cyber')->warning($request);
                return response()->json(
                    ['msg' => 'Your account was deleted.', 'errorCode' => $errorCode]);
            break;
            default:
                Log::channel('cyber')->warning("WRONG CREDENTIALS");
                Log::channel('cyber')->warning($request);
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

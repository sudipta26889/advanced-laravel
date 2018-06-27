<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use GuzzleHttp;
use App\Helpers\Helper;
use League\OAuth2\Server\AuthorizationServer;

class ApiAuthController extends ApiController
{
    public function __construct(Request $request, AuthorizationServer $server) {
        parent::__construct($request, $server);
    }

    public function redirect(Request $request, $client_id=3) {
        $query = http_build_query([
            'client_id' => $client_id,
            'redirect_uri' => env('APP_URL').'/api/callback',
            'response_type' => 'code',
            'scope' => '',
        ]);

        return redirect(env('APP_URL').'/oauth/authorize?'.$query);
    }
    public function callback(Request $request) {
        $http = new GuzzleHttp\Client;
        $response = $http->post(env('APP_URL').'/oauth/token', [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => '2',
                'client_secret' => 'zxJ7yIstAHeZgo8lfkIyCp8fsuSvZtq3WwA8SStW',
                'redirect_uri' => env('APP_URL').'/api/callback-received',
                'code' => $request->code,
            ],
        ]);
        return json_decode((string) $response->getBody(), true);
    }
    public function callbackReceived(Request $request) {
        echo "Received callback response";
        dd($request);
    }
    public function refresh(Request $request) {
        $http = new GuzzleHttp\Client;
        $response = $http->post(env('APP_URL').'/oauth/token', [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => '',
                'client_id' => '2',
                'client_secret' => 'zxJ7yIstAHeZgo8lfkIyCp8fsuSvZtq3WwA8SStW',
                'scope' => '',
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }
}

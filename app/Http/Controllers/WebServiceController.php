<?php

namespace App\Http\Controllers;

use App\Models\WebService;
use Google\Client;
use Illuminate\Http\Request;

class WebServiceController extends Controller
{

    public const DRIVE_SCOPES = ['https://www.googleapis.com/auth/drive', 'https://www.googleapis.com/auth/drive.file'];

    public function connect($name)
    {
        if ($name == 'google-drive') {

            $client = new Client();
            $config = config('services.google-drive');
            $client->setClientId($config['id']);
            $client->setClientSecret($config['secret']);
            $client->setRedirectUri($config['redirect']);

            $client->setScopes(self::DRIVE_SCOPES);

            $authUrl = $client->createAuthUrl();
            return response(['url' => $authUrl]);
        }
    }

    public function callback(Request $request)
    {
        $client = app(Client::class);
        $config = config('services.google-drive');
        $client->setClientId($config['id']);
        $client->setClientSecret($config['secret']);
        $client->setRedirectUri($config['redirect']);

        $accessToken = $client->fetchAccessTokenWithAuthCode($request->code);

        $service = WebService::create([
            'user_id' => auth()->user()->id,
            'token' => json_encode(['access_token' => $accessToken]),
            'name' => 'google-drive'
        ]);
        return $service;
    }
}

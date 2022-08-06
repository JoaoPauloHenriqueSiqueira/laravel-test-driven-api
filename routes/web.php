<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/drive', function () {
    $client = new Google\Client();
    $client->setClientId('757043411892-otegllo11uqv2um9luu6jodhqln7bte3.apps.googleusercontent.com');
    $client->setClientSecret('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');
    $client->setRedirectUri("http://bf6e-187-109-140-51.ngrok.io/drive/callback");
    $client->setScopes(['https://www.googleapis.com/auth/drive', 'https://www.googleapis.com/auth/drive.file']);

    $authUrl = $client->createAuthUrl();
    return redirect($authUrl);
});


Route::get('/drive/callback', function (){
    $code = request('code');
    $client = new Google\Client();
    $client->setClientId('757043411892-otegllo11uqv2um9luu6jodhqln7bte3.apps.googleusercontent.com');
    $client->setClientSecret('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');
    $client->setRedirectUri("http://bf6e-187-109-140-51.ngrok.io/drive/callback");
    $client->setScopes(['https://www.googleapis.com/auth/drive', 'https://www.googleapis.com/auth/drive.file']);

    $accessToken = $client->fetchAccessTokenWithAuthCode($code);
    return $accessToken;
});

Route::get('/upload', function(){
    $accessToken = 'ya29.A0AVA9y1t02xT0F0DDUpuJ8ttOTE_1NM3lfpOynMauuzy5GC4jKHHAEPSACXYcV59Y5xFIt7ZO8aSsVwASo13TiEQMH5YHWw1Bid_WUbVPcCfbt2lXOet1PaZbK4908FajBCfPTJOr0V0aZEy53Try7JGjDd8-aCgYKATASATASFQE65dr8nkMLxJmYft7LRk5UMD3kIQ0163';

    $client = new Google\Client();
    $client->setAccessToken($accessToken);

    $service = new Google\Service\Drive($client);

    $file = new Google\Service\Drive\DriveFile();

    DEFINE("TESTFILE", 'testfile-small.txt');
    if (!file_exists(TESTFILE)) {
        $fh = fopen(TESTFILE, 'w');
        fseek($fh, 1024 * 1024);
        fwrite($fh, "!", 1);
        fclose($fh);
    }

    $file->setName("Hello World!");
    $service->files->create(
        $file,
        [
            'data' => file_get_contents(TESTFILE),
            'mimeType' => 'application/octet-stream',
            'uploadType' => 'multipart'
        ]
    );






});
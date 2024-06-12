<?php
require 'vendor/autoload.php';
require 'config.php';
require 'functions.php';

use GuzzleHttp\Client;

$client = new Client(['timeout'  => 2.0]);
try {
    var_dump($_GET['code']);die;
    $response = $client->request('GET', 'https://accounts.google.com/.well-known/openid-configuration');
    $discoveryJson = json_decode((string)$response->getBody());
    $tokenEndpoint = $discoveryJson->token_endpoint;
    $response = $client->request('POST', $tokenEndpoint, ['form_params' => [
        'code' => $_GET['code'],
        'client_id' => GOOGLE_ID,
        'client_secret' => GOOGLE_SECRET,
        'redirect_uri' => 'http://localhost:8001/connect.php',
        'grant_type' => 'authorization_code'
    ]]);
    $accessToken = json_decode($response->getBody())->access_token;
    $userInfoEndpoint = $discoveryJson->userinfo_endpoint;
    $response = $client->request('GET', $userInfoEndpoint, [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken
        ]
    ]);
    $response = json_decode((string)$response->getBody());
    if ($response->email_verified === true) {
        if(isset(getUserByEmail($response->email)[0])) {
            session_start();
            $_SESSION['user'] = getUserByEmail($response->email)[0];
        }
        header('Location: index.php');
        exit;
    }
} catch (Exception $e) {
    var_dump('Une erreur est survenue :' . $e->getMessage());die;
}
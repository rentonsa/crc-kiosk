<?php

session_start();
include('GoogleClientApi/src/Google_Client.php');
include('GoogleClientApi/src/contrib/Google_AnalyticsService.php');

$scriptUri = "http://".$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF'];
echo $scriptUri;
$client = new Google_Client();

echo 'ok?';
$client->setAccessType('online'); // default: offline
echo 'ok?';
$client->setApplicationName('Test App');
echo 'ok?';
$client->setClientId('995860711068.apps.googleusercontent.com');
echo 'ok?';
$client->setClientSecret('Y3AsYiA3VqlcLnk3y3D42alI');
echo 'ok?';
$client->setRedirectUri($scriptUri);
echo 'ok?';
$client->setDeveloperKey('AIzaSyAgo6wn8_aehyNbNbgRUmGsUpTR5L6xC_c'); // API key
echo 'clients got';
// $service implements the client interface, has to be set before auth call
$service = new Google_AnalyticsService($client);

if (isset($_GET['logout'])) { // logout: destroy token
    unset($_SESSION['token']);
	die('Logged out.');
}

if (isset($_GET['code'])) { // we received the positive auth callback, get the token and store it in session
    $client->authenticate();
    $_SESSION['token'] = $client->getAccessToken();
}

if (isset($_SESSION['token'])) { // extract token from session and configure client
    $token = $_SESSION['token'];
    $client->setAccessToken($token);
}

if (!$client->getAccessToken()) { // auth call to google
    $authUrl = $client->createAuthUrl();
    header("Location: ".$authUrl);
    die;
}
echo 'Hello, world.';
?>
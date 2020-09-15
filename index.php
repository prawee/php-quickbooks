<?php
/*
 * Load vendor and dependencies
 */
require('./vendor/autoload.php');

/*
 * Load env
 */
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

/*
 * Env required
 */
$dotenv->required([
    'APP_ID',
    'AUTHORIZATION_REQUEST_URL',
    'TOKEN_ENPOINT_URL',
    'CLIENT_ID',
    'CLIENT_SECRET',
    'OAUTH_SCOPE',
    'OAUTH_REDIRECT_URI'
])->notEmpty();

/*
 * Test
 */
echo '<pre>'.print_r($_ENV, true).'</pre>';

use QuickBooksOnline\API\DataService\DataService;

session_start();

$dataService = DataService::Configure([
    'auth_mode' => 'oauth2',
    'ClientID' => $_ENV['CLIENT_ID'],
    'ClientSecret' => $_ENV['CLIENT_SECRET'],
    'RedirectURI' => $_ENV['OAUTH_REDIRECT_URI'],
    'scope' => $_ENV['OAUTH_SCOPE'],
    'baseUrl' => 'development'
]);

$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
$authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
echo $authUrl;

$_SESSION['authUrl'] = $authUrl;

echo '<pre>'.print_r($_SESSION, 10).'</pre>';
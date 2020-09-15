<?php
/*
 * Load vendor and dependencies
 */
require('./vendor/autoload.php');
use QuickBooksOnline\API\DataService\DataService;

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
 * Start session
 */
session_start();

/*
 * functional
 */
function processCode()
{
    $dataService = DataService::Configure([
        'auth_mode' => 'oauth2',
        'ClientID' => $_ENV['CLIENT_ID'],
        'ClientSecret' => $_ENV['CLIENT_SECRET'],
        'RedirectURI' => $_ENV['OAUTH_REDIRECT_URI'],
        'scope' => $_ENV['OAUTH_SCOPE'],
        'baseUrl' => 'development'
    ]);

    $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
    $parseUrl = parseAuthRedirectUrl($_SERVER['QUERY_STRING']);

    /*
     * update the OAuth2Token
     */
    $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($parseUrl['code'], $parseUrl['realmId']);
    $dataService->updateOAuth2Token($accessToken);

    /*
     * setting the accessToken for session variable
     */
    $_SESSION['sessionAccessToken'] = $accessToken;
}

function parseAuthRedirectUrl($url)
{
    parse_str($url, $qsArray);
    return [
        'code' => $qsArray['code'],
        'realmId' => $qsArray['realmId']
    ];
}

/*
 * running
 */
$result = processCode();
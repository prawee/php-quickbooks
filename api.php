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
 * Api
 */
function makeAPICall()
{
    $dataService = DataService::Configure([
        'auth_mode' => 'oauth2',
        'ClientID' => $_ENV['CLIENT_ID'],
        'ClientSecret' => $_ENV['CLIENT_SECRET'],
        'RedirectURI' => $_ENV['OAUTH_REDIRECT_URI'],
        'scope' => $_ENV['OAUTH_SCOPE'],
        'baseUrl' => 'development'
    ]);

    /*
     * retrieve the accessToken value from session variable
     */
    $accessToken = $_SESSION['sessionAccessToken'];

    /*
     * update the 0Auth2Token of the dataService object
     */
    $dataService->updateOAuth2Token($accessToken);
    $companyInfo = $dataService->getCompanyInfo();
    $address = "QBO API call Successful!! Response Company name: " . $companyInfo->CompanyName . " Company Address: " . $companyInfo->CompanyAddr->Line1 . " " . $companyInfo->CompanyAddr->City . " " . $companyInfo->CompanyAddr->PostalCode;
    print_r($address);
    return $companyInfo;
}

/*
 * Running
 */
$result = makeAPICall();
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
//echo '<pre>'.print_r($_ENV, true).'</pre>';

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
$_SESSION['authUrl'] = $authUrl;

//echo '<pre>'.print_r($_SESSION, 10).'</pre>';

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="apple-touch-icon icon shortcut" type="image/png" href="https://plugin.intuitcdn.net/sbg-web-shell-ui/6.3.0/shell/harmony/images/QBOlogo.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="assets/css/common.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<bod>
    <div class="container">
        <h1>
            <a href="http://developer.intuit.com">
                <img src="assets/images/quickbooks_logo_horz.png" class="img-responsive" />
            </a>
        </h1>
    </div>
</bod>
</html>

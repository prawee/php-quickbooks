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
//echo '<pre>'.print_r($_ENV, true).'</pre>';

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
//echo '<pre>'.print_r($_SESSION, true).'</pre>';

/*
 * Configure and Test
 */
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

/*
 * store the url in PHP session object
 */
$_SESSION['authUrl'] = $authUrl;

/*
 * set the access token using the auth object
 */
if (isset($_SESSION['sessionAccessToken']))
{
    $accessToken = $_SESSION['sessionAccessToken'];
    $accessTokenJson = [
        'token_type' => 'bearer',
        'access_token' => $accessToken->getAccessToken(),
        'refresh_token' => $accessToken->getRefreshToken(),
        'x_refresh_token_expires_in' => $accessToken->getRefreshTokenExpiresAt(),
        'expires_in' => $accessToken->getAccessTokenExpiresAt()
    ];
    //echo '<pre>'.print_r($accessTokenJson, true).'</pre>';
    $dataService->updateOAuth2Token($accessToken);
    $oauthLoginHelper = $dataService->getOAuth2LoginHelper();
    //$CompanyInfo = $dataService->getCompanyInfo();
    //echo '<pre>'.print_r($CompanyInfo, true).'</pre>';
}
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
    <script src="assets/js/api.js"></script>
    <script>
        let OAuthCode = function(url) {
            this.loginPopup = function(parameter) {
                this.loginPopupUri(parameter);
            }
            this.loginPopupUri = function(parameter) {
                const parameters = "location=1,width=800,height=650,left="+(screen.width - 800)/2+",top="+(screen.height - 650)/2;
                const win = window.open(url, 'connectPopup', parameters);
                const pollOAuth = window.setInterval(function() {
                    try {
                        if (win.document.URL.indexOf("code") != -1) {
                            window.clearInterval(pollOAuth);
                            win.close();
                            location.reload();
                        }
                    } catch (e) {
                        console.log(e);
                    }
                }, 100);
            }
        }

        const url = '<?=$authUrl?>';
        const oauth = new OAuthCode(url);
        const apiCall = new apiQB();
    </script>
</head>
<bod>
    <div class="container">
        <h1>
            <a href="http://developer.intuit.com" target="_blank">
                <img src="assets/images/quickbooks_logo_horz.png" class="img-responsive" />
            </a>
        </h1>

        <hr/>

        <div class="well text-center">
            <h1>QuickBooks sample application</h1>
            <h2>Demonstrate connect to QuickBooks flow and API request</h2>
        </div>

        <p class="text-center">
            If there is no access token or the access token is invalid, click the <b>Connect to QuickBooks</b> button below.
        </p>

        <?php
        $displayString = isset($accessTokenJson) ? $accessTokenJson : "No access token generated yet";
        ?>
        <pre id="accessToken" class="pre-code"><?=json_encode($displayString, JSON_PRETTY_PRINT)?></pre>

        <a href="#" class="imgLink" onclick="oauth.loginPopup()">
            <img src="assets/images/C2QB_green_btn_lg_default.png" width="178" />
        </a>

        <hr/>

        <h2>Make an API call</h2>
        <p class="text-center">
            if there is no access token or access token is invalid, click either the <b>Connect to QuickBooks</b> button above.
        </p>

        <pre id="apiResult"></pre>

        <button type="button" class="btn btn-success" onclick="apiCall.getCompanyInfo()">
            Get Company Info
        </button>
    </div>
</bod>
</html>

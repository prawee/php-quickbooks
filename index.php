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

echo '<pre>'.print_r($_SESSION, 10).'</pre>';

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
    </script>
</head>
<bod>
    <div class="container">
        <h1>
            <a href="http://developer.intuit.com">
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
        $displayString = isset($accessToken) ? $accessToken : "No access token generated yet";
        ?>
        <pre id="accessToken" class="pre-code"><?=json_encode($displayString, JSON_PRETTY_PRINT)?></pre>

        <a href="#" class="imgLink" onclick="oauth.loginPopup()">
            <img src="assets/images/C2QB_green_btn_lg_default.png" width="178"
        </a>
    </div>
</bod>
</html>

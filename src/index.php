<?php
require 'vendor/autoload.php';

use Jumbojett\OpenIDConnectClient;

session_start();

// Load our settings
require_once 'config.php';

// Configuration
$clientId = 'your-client-id';
$clientSecret = 'your-client-secret';
$providerUrl = 'https://your-identity-provider.com';

$oidc = new Jumbojett\OpenIDConnectClient($issuer, $cid, $secret);

# make this configurable - we want this when behind a proxy
$oidc->setHttpUpgradeInsecureRequests($upgrade_insecure_http_requests);

# else if above it true we need
#$oidc->setCertPath('/path/to/my.cert');

$oidc->setVerifyHost($verify_host);
$oidc->setVerifyPeer($verify_peer);

# add scopes based on config
$oidc->addScope($scopes);
$oidc->setRedirectURL($redirect_url);

# set the code challenge to ensure we use PKCE
$oidc->setCodeChallengeMethod('S256');

// Check for the authorization code in the URL
if (isset($_GET['code'])) {
    try {
        $oidc->authenticate();
        $_SESSION['user'] = $oidc->getVerifiedClaims();
        $_SESSION['idToken'] = $oidc->getIdToken();
        header('Location: index.php');
        exit();
    } catch (Exception $e) {
        echo 'Failed to get user information: ' . $e->getMessage();
        exit();
    }
}

// Handle login
if (isset($_GET['action']) && $_GET['action'] == 'login') {
    $oidc->authenticate();
    //$accessToken = $oidc->getAccessToken();
    //$idToken = $oidc->getIdTokenPayload();
    //$_SESSION['info'] = $info = $oidc->requestUserInfo();
    $_SESSION['user'] = $oidc->getVerifiedClaims();
    header('Location: index.php');
    exit();
}

// Handle logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    if (isset($_SESSION['user'])) {
        session_destroy();
        $oidc->signOut($_SESSION['idToken'],$redirect_url);
    } else {
        header('Location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello World PHP with OpenID Connect</title>
</head>
<body>
    <h1>Hello World PHP with OpenID Connect</h1>
    <?php if (isset($_SESSION['user'])): ?>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['user']->name); ?>!</p>
        <p><a href="?action=logout">Logout</a></p>
    <?php else: ?>
        <p><a href="?action=login">Login</a></p>
    <?php endif; ?>
    <p>debug info</p>
    <?php 
    echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';
    ?>
</body>
</html>

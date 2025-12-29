<?php
require 'vendor/autoload.php';

use Jumbojett\OpenIDConnectClient;

session_start();

// Handle logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    if (isset($_SESSION['idToken'])) {
        // Load config for logout
        require_once 'config.php';

        $oidc = new Jumbojett\OpenIDConnectClient($issuer, $cid, $secret);
        $oidc->setHttpUpgradeInsecureRequests($upgrade_insecure_http_requests);
        $oidc->setVerifyHost($verify_host);
        $oidc->setVerifyPeer($verify_peer);

        $idToken = $_SESSION['idToken'];
        session_destroy();
        $oidc->signOut($idToken, $redirect_url);
    } else {
        session_destroy();
        header('Location: index.php');
        exit();
    }
}

// Check authentication status
if (isset($_SESSION['user']) && isset($_SESSION['idToken'])) {
    // User is authenticated
} else {
    $_SESSION['return'] = 'index.php';
    header('Location: auth.php');
    die();
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
    <?php endif; ?>
    <p>debug info</p>
    <?php 
    echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';
    ?>
</body>
</html>

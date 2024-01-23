<?php

session_start();

// Load our settings
require_once 'config.php';

// Autoload classes
require 'vendor/autoload.php';

use Jumbojett\OpenIDConnectClient;

$oidc = new Jumbojett\OpenIDConnectClient($issuer, $cid, $secret);

# make this configurable - we want this when behind a proxy
$oidc->setHttpUpgradeInsecureRequests($upgrade_insecure_http_requests);

# else if above it true we need
#$oidc->setCertPath('/path/to/my.cert');

$oidc->setVerifyHost($verify_host);
$oidc->setVerifyPeer($verify_peer);

# add scopes based on config
$oidc->addScope(['openid','profile','berkeley_edu_default']);
$oidc->setRedirectURL($redirect_url);

# set the code challenge to ensure we use PKCE
$oidc->setCodeChallengeMethod('S256');
$oidc->authenticate();

try {
  $info = $oidc->requestUserInfo();
  $id = $oidc->requestUserInfo('id');
  $sub = $oidc->requestUserInfo('sub');
  $attrs = array();
  foreach($info->attributes as $key=> $value) {
      if(is_array($value)){
              $v = implode(', ', $value);
      }else{
              $v = $value;
      }
      $attrs[$key] = $v;
  }
} catch (\Jumbojett\OpenIDConnectClientException $e) {
  echo $e;
}

$_SESSION['id'] = $id;
$_SESSION['sub'] = $sub;
$_SESSION['attrs'] = $attrs;

if (isset($_SESSION['return'])) {
  # Redirect to remove the OIDC code
  header('Location: '.$_SESSION['return']);
  die();
}

?>

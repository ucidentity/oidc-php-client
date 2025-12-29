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
$oidc->addScope($scopes);
$oidc->setRedirectURL($redirect_url);

if($pkce === true){
  # set the code challenge to ensure we use PKCE
  $oidc->setCodeChallengeMethod('S256');
}

$oidc->authenticate();

try {
  $accessToken = $oidc->getAccessToken();
  $idToken = $oidc->getIdTokenPayload();
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
  
  $object = new stdClass();
  $object->access_token = $accessToken;
  $object->id_token = $idToken;
  $object->userInfo = $info;
  $jsonResp = json_encode($object, JSON_PRETTY_PRINT);


} catch (\Jumbojett\OpenIDConnectClientException $e) {
  echo $e;
}

$_SESSION['id'] = $id;
$_SESSION['sub'] = $sub;
$_SESSION['attrs'] = $attrs;
$_SESSION['jsonResp'] = $jsonResp;

// Also set session variables for index.php compatibility
$_SESSION['user'] = $oidc->getVerifiedClaims();
$_SESSION['idToken'] = $oidc->getIdToken();

if (isset($_SESSION['return'])) {
  # Redirect to remove the OIDC code
  header('Location: '.$_SESSION['return']);
  die();
} else {
  header('Location: index.php');
  die();
}

?>

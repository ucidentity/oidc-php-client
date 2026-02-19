<?php

session_start();

// Load our settings
require_once 'config.php';

// Autoload classes
require 'vendor/autoload.php';

use Jumbojett\OpenIDConnectClient;

$oidc = new Jumbojett\OpenIDConnectClient($issuer, $cid, $secret);

# Make this configurable - we want this when behind a proxy
$oidc->setHttpUpgradeInsecureRequests($upgrade_insecure_http_requests);

# Else if above is true we need
#$oidc->setCertPath('/path/to/my.cert');

$oidc->setVerifyHost($verify_host);
$oidc->setVerifyPeer($verify_peer);

# Add scopes based on config
$oidc->addScope($scopes);
$oidc->setRedirectURL($redirect_url);

if($pkce === true){
  # Set the code challenge to ensure we use PKCE
  # This might be unnecessary when using a clientSecret
  # Potentialy make this a bit smarter
  $oidc->setCodeChallengeMethod('S256');
}

# Control which client auth method is used at the token endpoint.
# Defaults to client_secret_post so credentials are sent only in the POST body.
# This is required for Okta compatibility when PKCE is enabled, since using
# client_secret_basic causes the client_id to appear in both the Authorization
# header and the POST body (a jumbojett bug), which Okta rejects.
$oidc->setTokenEndpointAuthMethodsSupported([$client_auth_method]);

$oidc->authenticate();

try {
  $accessToken = $oidc->getAccessToken();
  $idToken = $oidc->getIdTokenPayload();
  $info = $oidc->requestUserInfo();
  $id = $oidc->requestUserInfo('id');
  $sub = $oidc->requestUserInfo('sub');
  $attrs = array();
  // Apereo CAS wraps attributes under an 'attributes' key; standard OIDC providers
  // (e.g. Okta) return claims as flat top-level properties on the userinfo object.
  $raw_attrs = isset($info->attributes) ? (array)$info->attributes : (array)$info;
  foreach($raw_attrs as $key => $value) {
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

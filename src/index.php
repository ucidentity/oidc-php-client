<?php

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

# add scopes based on config
$oidc->addScope('openid');
$oidc->addScope('profile');
$oidc->addScope('berkeley_edu_default');
$oidc->setRedirectURL($redirect_url);
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
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>OpenID Connect: Released Attributes</title>

</head>

<body>
  <p>Hello,
    <?php echo $id; ?>
  </p>

  <!-- Intro -->
  <div class="banner">
    <div class="container">
      <h1 class="section-heading">Claims</h1>

      <h3>
        Claims sent back from OpenID Connect
      </h3>
      <br />
    </div>
  </div>

  <!-- Claims -->
  <div class="content-section-a" id="claims">
    <div class="container">
      <div class="row">

        <table class="table" style="width:80%;" border="1">
          <?php foreach ($attrs as $key=>$value): ?>
          <tr>
            <td data-toggle="tooltip" title=<?php echo $key; ?>>
              <?php echo $key; ?>
            </td>
            <td data-toggle="tooltip" title=<?php echo $value; ?>>
              <?php echo $value; ?>
            </td>
          </tr>
          <?php endforeach; ?>

        </table>
      </div>
    </div>
  </div>

</body>

</html>
<?php

// Defaults
$issuer = 'https://localhot:8443/cas/oidc';
$cid = 'client';
$secret = 'secret';
$cert_path = '/path/to/my.cert';
$upgrade_insecure_http_requests = true;
$redirect_url = 'http://localhost/index.php';
$verify_host = true;
$verify_peer = true;

// Override defaults
foreach (getenv() as $key => $value) {
    if ($key == 'ISSUER') {
        $issuer = $value;
    }
    if ($key == 'CID') {
        $cid = $value;
    }
    if ($key == 'SECRET') {
        $secret = $value;
    }
    if ($key == 'SERVER_CA_PATH') {
        $cert_path = $value;
    }
    if ($key == 'UPGRADE_HTTP') {
        $upgrade_insecure_http_requests = (bool)$value;
    }
    if ($key == 'REDIRECT_URL') {
        $redirect_url = $value;
    }
    if ($key == 'VERIFY_HOST') {
        $verify_host = (bool)$value;
    }
    if ($key == 'VERIFY_PEER') {
        $verify_peer = (bool)$value;
    }
}

?>
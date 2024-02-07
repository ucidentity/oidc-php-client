<?php

// Defaults
$issuer = 'https://localhot:8443/cas/oidc';
$cid = 'client';
$secret = 'secret';
$cert_path = '/path/to/my.cert';
$upgrade_insecure_http_requests = true;
$redirect_url = 'http://localhost/auth.php';
$verify_host = true;
$verify_peer = true;

// Override defaults
foreach (getenv() as $key => $value) {
    if ($key == 'ISSUER') {
        $issuer = str_replace('"','',$value);
    }
    if ($key == 'CID') {
        $cid = str_replace('"','',$value);
    }
    if ($key == 'SECRET') {
        $secret = str_replace('"','',$value);
    }
    if ($key == 'SERVER_CA_PATH') {
        $cert_path = str_replace('"','',$value);
    }
    if ($key == 'UPGRADE_HTTP') {
        $upgrade_insecure_http_requests = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
    if ($key == 'REDIRECT_URL') {
        $redirect_url = str_replace('"','',$value);
    }
    if ($key == 'VERIFY_HOST') {
        $verify_host = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
    if ($key == 'VERIFY_PEER') {
        $verify_peer = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}

?>
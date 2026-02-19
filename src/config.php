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
$return_json = false;
$pkce = true;
$scopes = ['openid','profile'];
// Use client_secret_post by default so credentials are sent only in the POST body.
// This avoids the "multiple client credentials" error from Okta when PKCE is enabled
// (client_secret_basic would put credentials in both the Authorization header and, due to
// a jumbojett PKCE interaction, also in the POST body).
$client_auth_method = 'client_secret_post';

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
    if ($key == 'RETURN_JSON') {
        $return_json = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
    if ($key == 'PKCE') {
        $pkce = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
    if ($key == 'SCOPES') {
        $scopes = explode(',', $value);
        $scopes = array_map('trim', $scopes);
    }
    if ($key == 'CLIENT_AUTH_METHOD') {
        $client_auth_method = str_replace('"','',$value);
    }
}

?>
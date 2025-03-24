# OIDC PHP Client

Example client using the [PHP OpenID Connect Basic Client](https://github.com/jumbojett/OpenID-Connect-PHP)

This example is not intended for use in production.

## Environment Variables

You can specify the following environment variables to control how the PHP application works:

```shell
ISSUER="https://auth.berkeley.edu/cas/oidc"
CID=client_name
SECRET="**********"
UPGRADE_HTTP=true/false
REDIRECT_URL="http://localhost"
VERIFY_HOST=true/false
VERIFY_PEER=true/false
PKCE=true/false
SCOPES="openid,profile,berkeley_edu_default"
```

## Running

```shell
git pull https://github.com/ucidentity/oidc-php-client
cd oidc-php-client
docker build --tag=oidc-php-client .
```

With Defaults

```shell
docker run -p 80:80 oidc-php-client
```

With env vars

```shell
docker run --rm \
    --publish 80:80 \
    --env ISSUER="https://auth-dev.calnet.berkeley.edu/cas/oidc" \
    --env REDIRECT_URL="http://localhost" \
    --env UPGRADE_HTTP=false \
    --env CID="client" \
    --env SECRET=$OIDC_SECRET \
    --name oidc-client \
    ghcr.io/ucidentity/oidc-php-client:latest
```

Go to http://localhost

Another example using a local CAS host

```shell
docker run --rm \
    --publish 8000:80 \
    --env ISSUER="https://cas-host:8443/cas/oidc" \
    --env REDIRECT_URL="http://oidc-client:8000" \
    --env UPGRADE_HTTP=false \
    --env CID="client" \
    --env SECRET="password" \
    --name oidc-client \
    oidc-php-client:latest
```

Go to http://localhost:8000

Example overriding default Apache configuration

```shell
docker run --rm \
    --publish 8000:8000 \
    -v ./apache2/ports.conf:/etc/apache2/ports.conf \
    -v ./apache2/000-default.conf:/etc/apache2/sites-enabled/000-default.conf \
    --env ISSUER="https://cas-host:8443/cas/oidc" \
    --env REDIRECT_URL="http://oidc-client:8000" \
    --env UPGRADE_HTTP=false \
    --env CID="client" \
    --env SECRET="password" \
    --env SCOPES="openid,profile,berkeley_edu_default,berkeley_edu_employee_number"
    --name oidc-client \
    oidc-php-client:latest
```

Example with local networks

```shell
docker run --rm \
    --publish 80:80 \
    --env ISSUER="https://cas-host:8443/cas/oidc" \
    --env REDIRECT_URL="http://oidc-client" \
    --env UPGRADE_HTTP="false" \
    --env CID="client" \
    --env SECRET="secret" \
    --env UPGRADE_HTTP="false" \
    --env VERIFY_HOST="false" \
    --env VERIFY_PEER="false" \
    --env SCOPES="openid,profile,berkeley_edu_default,berkeley_edu_employee_number" \
    --name oidc-client \
    --network cas-test \
    oidc-php-client:latest

docker run --rm \
    --publish 80:80 \
    --env ISSUER="https://cas-host:8443/cas/oidc" \
    --env REDIRECT_URL="http://oidc-client" \
    --env UPGRADE_HTTP="false" \
    --env CID="client" \
    --env SECRET="secret" \
    --env UPGRADE_HTTP="false" \
    --env VERIFY_HOST="false" \
    --env VERIFY_PEER="false" \
    --env SCOPES="openid,profile,berkeley_edu_default,berkeley_edu_employee_number" \
    --name oidc-client \
    --network cas-test \
    oidc-php-client:latest
```

## Troubleshooting

The following generally means there is a mismatch between the client ID and/or client secret between the SP and RP

```
( ! ) Fatal error: Uncaught TypeError: property_exists(): Argument #1 ($object_or_class) must be of type object|string, null given in /var/www/html/vendor/jumbojett/openid-connect-php/src/OpenIDConnectClient.php on line 330
```
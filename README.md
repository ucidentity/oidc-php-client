# OIDC PHP Client

Example client using the [PHP OpenID Connect Basic Client](https://github.com/jumbojett/OpenID-Connect-PHP)

This example is not intended for use in production.

## Environment Variables

You can specify the following environment variables to control how the PHP application works:

```shell
ISSUER="https://auth.berkeley.edu/cas/oidc"
CID=client_name
SECRET=**********
SERVER_CA_PATH=
UPGRADE_HTTP=false
REDIRECT_URL="http://localhost/index.php"
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
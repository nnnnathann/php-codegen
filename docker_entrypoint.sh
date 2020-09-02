#!/bin/sh

if [ -n "${GITHUB_OAUTH_TOKEN}" ]; then
    composer config -g github-oauth.github.com "${GITHUB_OAUTH_TOKEN}"
fi
composer install --prefer-dist --no-interaction

echo "$@"

command=${*:-"bash"}
echo "running command: ${command}"
eval "${command}"
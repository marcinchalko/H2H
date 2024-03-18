#!/usr/bin/env sh
set -eu
sleep 4
envsubst '${VIRTUAL_HOST} ${FPM_CONTAINER_NAME}' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf

exec "$@"

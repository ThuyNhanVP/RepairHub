#!/bin/sh
set -e

echo "[repairhub-entrypoint] starting"

if [ ! -f /var/www/html/public/build/manifest.json ]; then
    echo "[repairhub-entrypoint] copying built assets to /var/www/html/public/build"
    mkdir -p /var/www/html/public/build
    cp -R /opt/repairhub-build/public/build/. /var/www/html/public/build/
fi

exec "$@"

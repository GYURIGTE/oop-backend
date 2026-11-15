#!/bin/sh
set -e

PORT="${PORT:-80}"

# Render assigns a port at runtime, so make Apache listen on it.
sed -ri "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Cache the runtime configuration (env vars are present at this point).
php artisan config:cache

# Apply any pending migrations on every boot.
php artisan migrate --force

# Seed demo data only on the first deploy: set SEED_DATABASE=true once,
# then change it to false so restarts don't reset the data.
if [ "${SEED_DATABASE:-false}" = "true" ]; then
  php artisan db:seed --force
fi

exec apache2-foreground

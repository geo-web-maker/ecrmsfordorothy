#!/bin/sh
set -e

export PORT="${PORT:-8080}"

if [ -z "$APP_URL" ] && [ -n "$RENDER_EXTERNAL_URL" ]; then
    export APP_URL="$RENDER_EXTERNAL_URL"
fi

envsubst '${PORT}' < /etc/nginx/templates/default.conf.template > /etc/nginx/conf.d/default.conf

chown -R www-data:www-data storage bootstrap/cache

if [ -n "$APP_KEY" ]; then
    php artisan storage:link --force 2>/dev/null || true

    if [ -n "$DB_URL" ] || [ -n "$DB_HOST" ]; then
        echo "Running migrations..."
        i=0
        while [ "$i" -lt 30 ]; do
            if php artisan migrate --force --no-interaction; then
                break
            fi
            i=$((i + 1))
            if [ "$i" -eq 30 ]; then
                echo "Database migration failed after 30 attempts."
                exit 1
            fi
            sleep 2
        done
    fi

    php artisan config:cache --no-interaction
    php artisan route:cache --no-interaction
    php artisan view:cache --no-interaction
fi

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

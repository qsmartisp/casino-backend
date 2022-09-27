#!/bin/sh

cd /var/www

# php artisan migrate:fresh --seed
php artisan storage:link && \
php artisan migrate --force && \
php artisan migrate:status && \
php artisan key:generate && \
php artisan jwt:secret && \
php artisan cache:clear && \
php artisan config:cache && \
php artisan route:cache && \
/usr/bin/supervisord -c /etc/supervisord.conf

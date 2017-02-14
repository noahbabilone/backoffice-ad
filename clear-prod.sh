php app/console cache:clear --env=prod
php app/console cache:warmup --env=prod
php app/console assets:install --env=prod
php app/console assetic:dump --env=prod

chown -R www-data:www-data .

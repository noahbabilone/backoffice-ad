php app/console cache:clear
php app/console cache:warmup
php app/console assets:install --symlink
php app/console assetic:dump

chown -R www-data:www-data .


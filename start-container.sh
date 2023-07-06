#!/bin/bash

chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

chown -R www-data:www-data /var/www/html/logs
chmod -R 775 /var/www/html/logs

php-fpm

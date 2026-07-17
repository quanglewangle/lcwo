#!/bin/sh
set -e

sudo apt-get install -y php php-mysql php-mbstring php-gd
sudo sed -i 's/short_open_tag = Off/short_open_tag = On/' /etc/php/8.3/cli/php.ini
sudo mysql -e "CREATE DATABASE IF NOT EXISTS LCWO; CREATE USER IF NOT EXISTS 'lcwo'@'localhost' IDENTIFIED BY 'lcwo'; GRANT ALL PRIVILEGES ON LCWO.* TO 'lcwo'@'localhost'; FLUSH PRIVILEGES;"

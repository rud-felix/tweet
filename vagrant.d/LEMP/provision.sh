#!/bin/bash


### Install software

# Update sources list
sudo apt-get update

# Nginx
sudo apt-get install -y nginx

# PHP
sudo add-apt-repository -y ppa:ondrej/php5
sudo apt-get install -y php5-fpm php5-curl php5-gd php5-intl php-pear php5-imagick php5-mcrypt php5-xdebug php5-cli php-apc php5-mysql php5-memcache

# MongoDB
#sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 7F0CEB10
#echo "deb http://repo.mongodb.org/apt/ubuntu trusty/mongodb-org/3.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-3.0.list
#sudo apt-get update
#sudo apt-get install -y mongodb-org
#export LC_ALL=C

# Extensions
#sudo pecl install mongo

# Packages
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
sudo debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'

sudo apt-get update
sudo apt-get install -y mysql-server
sudo apt-get install -y mysql-client

# phpunit
wget https://phar.phpunit.de/phpunit.phar
sudo chmod +x phpunit.phar
sudo mv phpunit.phar /usr/local/bin/phpunit

### Configure services

# PHP
sudo cp /vagrant/vagrant.d/LEMP/php_fpm.ini /etc/php5/fpm/php.ini
sudo cp /vagrant/vagrant.d/LEMP/php_www.conf /etc/php5/fpm/pool.d/www.conf


### Finishing setup

# Clear cache
sudo apt-get autoremove

# Restart services
sudo service php5-fpm restart
sudo service nginx restart

exit 0

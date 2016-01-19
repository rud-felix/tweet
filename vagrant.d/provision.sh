#!/bin/bash

### Install software

# update sources list
sudo apt-get update

# MC
sudo apt-get install -y mc

# Git
sudo apt-get install -y git

# Ant
sudo apt-get install -y ant

# curl
sudo apt-get install -y curl

# Composer
cd ~ && curl -sS https://getcomposer.org/installer | php
sudo mv ~/composer.phar /usr/bin/composer
sudo chmod +x /usr/bin/composer


### Configure services

# Nginx
sudo cp /vagrant/vagrant.d/LEMP/nginx_config /etc/nginx/sites-available/default
sudo ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Hosts
sudo cp /vagrant/vagrant.d/hosts /etc/hosts

# SSH
sudo rm -rf /home/vagrant/.ssh
mkdir /home/vagrant/.ssh
cp /vagrant/vagrant.d/ssh/* /home/vagrant/.ssh/


### Deploy project

# Configuring
# cp /vagrant/ant.d/build.properties.template /vagrant/ant.d/build.properties
# ant -f /vagrant/ant.d/build.xml init

# symfony project permissions
sudo chmod 777 -R /vagrant/app/cache /vagrant/app/logs

### Restart services

# Nginx
sudo service nginx restart

# Apache2
sudo service apache2 restart

# PHP-FPM
sudo service php5-fpm restart

exit 0

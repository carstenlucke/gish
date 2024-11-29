#!/bin/bash
cd ~

##########################
# Script for Kali 2023.2 #
##########################

sudo apt-get update
sudo DEBIAN_FRONTEND=noninteractive apt-get install -y php-curl php-mbstring php-xml mariadb-plugin-provider-bzip2
sudo DEBIAN_FRONTEND=noninteractive apt-get upgrade -y
# sudo apt-get --with-new-pkgs upgrade

echo "+ + + Installation der Datenbank für mutillidae + + +"
sudo /etc/init.d/mariadb start
# set default MySql root password to "kali"
sudo mysql -uroot -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'kali'; flush privileges;"
# create Mutillidae database
sudo mysql -uroot -p"kali" -e "CREATE DATABASE mutillidae /*\!40100 DEFAULT CHARACTER SET utf8 */;"

# php settings must be insecure
PHPINI="/etc/php/8.2/apache2/php.ini"

allow_url_include=On
allow_url_fopen=On

for key in allow_url_include allow_url_fopen
do
 sudo sed -i "s/^\($key\).*/\1 $(eval echo = \${$key})/" $PHPINI
done
echo "+ + + Checking PHP setting changes + + +"
cat $PHPINI | grep allow_url_


# Checkout files from github repo
git clone https://github.com/carstenlucke/gish.git
cd gish
sudo mv /var/www/html /var/www/html-BACKUP
sudo mv var_www_html /var/www/html
sudo /etc/init.d/apache2 start

# Enable mysql and apache service
sudo systemctl enable mysql
sudo systemctl enable apache2

sudo systemctl daemon-reload
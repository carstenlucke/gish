#!/bin/bash
cd ~

sudo apt-get update
sudo apt-get install -y php-curl php-mbstring php-xml
sudo apt-get upgrade -y
# sudo apt-get --with-new-pkgs upgrade

echo "+ + + Installation der Datenbank für mutillidae + + +"
sudo /etc/init.d/mariadb start
# set default MySql root password to "kali"
sudo mysql -uroot -p -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'kali'; flush privileges;"
echo "DB-root Kennwort eingeben (einfach ENTER, wenn nicht anders gesetzt!"
sudo mysql -uroot -p"kali" -e "CREATE DATABASE mutillidae /*\!40100 DEFAULT CHARACTER SET utf8 */;"

# php settings must be insecure
allow_url_include=On
allow_url_fopen=On
for key in allow_url_include allow_url_fopen
do
 sed -i "s/^\($key\).*/\1 $(eval echo = \${$key})/" /etc/php/7.4/apache2/php.ini
done
echo "+ + + Checking PHP setting changes + + +"
cat /etc/php/7.4/apache2/php.ini | grep allow_url_

git clone https://github.com/carstenlucke/gish.git
cd gish
sudo mv /var/www/html /var/www/html-BACKUP
sudo mv var_www_html /var/www/html
sudo /etc/init.d/apache2 start

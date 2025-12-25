#!/bin/bash

# WICHTIG: Dieses Script MUSS als normaler User ausgeführt werden (nicht als root)!
# Das Script verwendet sudo für Befehle, die root-Rechte benötigen.

if [ "$EUID" -eq 0 ]; then
    echo "FEHLER: Dieses Script darf NICHT als root ausgeführt werden!"
    echo "Bitte als normaler User ausführen: ./preparevm.sh"
    exit 1
fi

cd ~

echo "+ + + Herunterladen der GISH Video-Materialien + + +"
VIDEO_URL="https://gish-vids.lucke.info"
VIDEO_DIR="$HOME/gish-videos"

# Erstelle Zielverzeichnis
mkdir -p "$VIDEO_DIR"

# Lade Videos mit wget herunter (Mirror-Modus für Verzeichnisstruktur)
wget --no-verbose \
     --recursive \
     --no-parent \
     --no-host-directories \
     --cut-dirs=0 \
     --reject "index.html*" \
     --directory-prefix="$VIDEO_DIR" \
     "$VIDEO_URL/"

echo "Videos wurden nach $VIDEO_DIR heruntergeladen"

sudo apt-get update
sudo DEBIAN_FRONTEND=noninteractive apt-get install -y php-curl php-mbstring php-xml mariadb-plugin-provider-bzip2
sudo DEBIAN_FRONTEND=noninteractive apt-get upgrade -y

echo "+ + + Installation der Datenbank für mutillidae + + +"
sudo /etc/init.d/mariadb start
# set default MySql root password to "kali"
sudo mysql -uroot -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'kali'; flush privileges;"
# Note: Database and tables will be created via set-up-database.php after Apache starts

# php settings must be insecure
# Find php.ini used by Apache (use latest version if multiple exist)
PHPINI=$(find /etc/php -type f -path "*/apache2/php.ini" 2>/dev/null | sort -V | tail -n1)

if [ -z "$PHPINI" ]; then
    echo "Error: Could not find Apache's php.ini file"
    exit 1
fi

echo "Using PHP configuration file: $PHPINI"

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

# Initialize Mutillidae database with tables and data
echo "+ + + Initialisierung der Mutillidae Datenbank + + +"
sleep 2  # Give Apache a moment to start
curl -s http://localhost/mutillidae/set-up-database.php > /dev/null
if [ $? -eq 0 ]; then
    echo "Mutillidae Datenbank erfolgreich initialisiert"
else
    echo "WARNUNG: Fehler bei der Initialisierung der Mutillidae Datenbank"
    echo "Bitte manuell http://localhost/mutillidae/set-up-database.php aufrufen"
fi

# Enable mysql and apache service
sudo systemctl enable mysql
sudo systemctl enable apache2

sudo systemctl daemon-reload
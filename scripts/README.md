# preparevm.sh — Beschreibung

Dieses Dokument erklärt, was das Skript `preparevm.sh` im gleichen Verzeichnis macht, wie es verwendet werden sollte und welche Risiken / Hinweise zu beachten sind.

## Zweck

`preparevm.sh` ist ein kurzes Setup-Skript, das für eine frische Kali-VM vorbereitet ist. Es installiert notwendige PHP-/Datenbank-Pakete, richtet MariaDB für die Mutillidae-Demo ein, passt PHP-Einstellungen an, und legt die Webdateien des Projekts unter `/var/www/html` bereit.

## Was das Skript macht (Schritte)

1. Wechselt ins Home-Verzeichnis des ausführenden Benutzers.
2. Führt `apt-get update` aus.
3. Installiert (non-interactive) folgende Pakete:
   - `php-curl`, `php-mbstring`, `php-xml`
   - `mariadb-plugin-provider-bzip2`
4. Führt `apt-get upgrade -y` aus.
5. Startet den MariaDB-Dienst.
6. Setzt das MariaDB-Root-Passwort auf `kali` mittels:
   ```sql
   ALTER USER 'root'@'localhost' IDENTIFIED BY 'kali';
   flush privileges;
   ```
7. Erstellt eine Datenbank `mutillidae` mit UTF-8 Standard-Zeichensatz.
8. Ändert in der PHP-Konfiguration (`/etc/php/8.2/apache2/php.ini`) zwei unsichere Einstellungen:
   - `allow_url_include = On`
   - `allow_url_fopen = On`
   und prüft die vorgenommenen Änderungen.
9. Klont dieses Git-Repository (`https://github.com/carstenlucke/gish.git`) in das Home-Verzeichnis, bewegt dann die vorhandene `/var/www/html` nach `/var/www/html-BACKUP` und verschiebt `var_www_html` aus dem Repository nach `/var/www/html`.
10. Startet den Apache2-Dienst.

## Verwendung

- Ausführen als normaler Benutzer (das Skript nutzt `sudo` für Befehle, die erhöhte Rechte benötigen):

```bash
chmod +x preparevm.sh
./preparevm.sh
```

- Das Skript ist nicht interaktiv und verwendet `DEBIAN_FRONTEND=noninteractive` bei Paketinstallationen.

## Annahmen

- Das System ist eine Debian/Ubuntu-basierte Distribution (Kali Linux) mit `apt`.
- PHP-Version im Skript: `/etc/php/8.2/apache2/php.ini`. Falls eine andere PHP-Version installiert ist, muss der Pfad angepasst werden.
- MariaDB ist installiert und die Init-Skripte befinden sich unter `/etc/init.d/`.
- Im Repository gibt es einen Ordner `var_www_html`, der die Web-Dateien enthält.

## Sicherheits- und Betriebs-Hinweise

- Das Skript setzt das MariaDB-Root-Passwort auf den klartext Wert `kali`. Das ist nur für Test- / Demo-Umgebungen geeignet. In produktiven Umgebungen ist das unsicher.
- `allow_url_include = On` und `allow_url_fopen = On` machen PHP anfällig für Remote-Include / Datei-Upload-Angriffe. Diese Einstellungen sind für unsichere Demo-Anwendungen (wie Mutillidae) absichtlich gesetzt, dürfen aber niemals in Produktivsystemen verwendet werden.
- Prüfe vor dem Überschreiben von `/var/www/html`, ob dort wichtige Daten liegen. Das Skript verschiebt die vorhandene Installation nach `/var/www/html-BACKUP`.
- Das Skript klont das Repo aus dem Internet; prüfe vor Verwendung die Integrität/Quelle.

# Kali Linux VM installieren

## Virtual Box installieren: https://www.virtualbox.org/

- Präferenz: Pre-built Virtual Machines installieren: https://www.kali.org/get-kali/#kali-virtual-machines

- Alternative - Kali Linux aus einem Image installieren: https://www.kali.org/get-kali/#kali-installer-images

## Auf MacOS, wenn Parallels Desktop verwendet wird: 
... einfach über den VM-Assistenten Kali installieren (Parallels Desktop kann Debian, Kali, Windows, etc. installieren). Ansonsten VirtualBox verwenden.

Nach der Installation in Kali Linux einloggen.

---

# Umgebung für die Vorlesung (Grundlagen der Informationssicherheit, kurz GISH) installieren.

Inhalte sind zu finden unter: https://github.com/carstenlucke/gish

- Hier ist jeglicher Code abgelegt
- Hier findet sich auch die vorliegende Installationsanleitung

1. Kali starten
2. Einloggen
   - Standard-Anmeldedaten: `kali/kali` (bei einigen Images).
   - Hinweis: Während der Installation musst Du höchstwahrscheinlich ein Kennwort für den Benutzer festlegen. Alternativ kann das Kennwort in den Hinweisen zum Image-Download genannt sein.
3. Terminal (normaler User) starten
4. Im Terminal folgende Befehle ausführen:

**WICHTIG:** Das Script muss als **normaler User** (nicht als root) ausgeführt werden! Das Script wird bei Bedarf selbst `sudo` verwenden.

```bash
wget https://raw.githubusercontent.com/carstenlucke/gish/main/scripts/preparevm.sh
chmod 755 preparevm.sh
./preparevm.sh
```

## Was macht das preparevm.sh Script?

Das Script führt folgende Konfigurationen und Installationen automatisch durch:

- **Locale- und Tastatureinstellungen:** Konfiguration auf Deutsch (de_DE.UTF-8)
- **Video-Materialien:** Download aller Vorlesungsvideos von `https://gish-vids.lucke.info/` nach `~/gish-videos`
- **System-Update:** Aktualisierung aller Pakete (`apt-get update` und `upgrade`)
- **PHP-Installation:** Installation notwendiger PHP-Erweiterungen (curl, mbstring, xml)
- **MariaDB-Setup:** Start der Datenbank und Setzen des root-Passworts auf `kali`
- **PHP-Konfiguration:** Anpassung der Sicherheitseinstellungen für Übungszwecke (`allow_url_include`, `allow_url_fopen`)
- **GISH-Repository:** Klonen des Repositories und Deployment nach `/var/www/html`
- **Apache-Webserver:** Start und automatisches Aktivieren des Webservers
- **Mutillidae-Datenbank:** Automatische Initialisierung der Übungsdatenbank
- **Service-Aktivierung:** Automatischer Start von MySQL und Apache beim Systemstart

5. Browser in Kali starten und `localhost` aufrufen
6. Die Startseite sollte *Guestbook* und *mutillidae* als Links anzeigen

---

> **Hinweis für Parallels-Benutzer und Apple Silicon Macs:**
>
> Falls Du Parallels verwendest oder Probleme bei der Installation von Parallels Tools bzw. mit Kali auf Apple Silicon auftrittest, kann die Datei [Installation Kali und Parallels Tools für GISH auf Apple Silicon Mac.md](docs/Installation%20Kali%20und%20Parallels%20Tools%20für%20GISH%20auf%20Apple%20Silicon%20Mac.md) im Repository zusätzliche Hilfe bieten.

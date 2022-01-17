# Anleitung zur Vorbereitung von KALI

1. Kali Linux herunterladen und im Hypervisor Deiner Wahl starten (e.g., VirtualBox, VMWare Fusion, etc.)
2. Kali starten
3. Einloggen mit kali/kali
4. Terminal (normaler User) starten
5. Im Terminal folgende Befehle ausführen:

* `wget https://raw.githubusercontent.com/carstenlucke/gish/main/scripts/preparevm.sh`
* `chmod 755 preparevm.sh`
* `./preparevm.sh`

6. Browser in Kali starten und `localhost` aufrufen
7. Die Startseite sollte *Guestbook* und *mutillidae* als Links anzeigen
8. Den Link für *mutillidae* anklicken. Die Seite "bemängeln", dass die Datenbank nicht installiert ist. Hier dann einfach den ersten Link zum Setup der Datenbank anklicken. Es sollte nun alles installiert werden und dann die Startseite von Mutillidae erscheinen.

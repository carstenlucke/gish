# Anleitung zur Vorbereitung von KALI

1. Kali Linux herunterladen und im Hypervisor Deiner Wahl starten (e.g., VirtualBox, VMWare Fusion, etc.)
2. Kali starten
3. Einloggen mit kali/kali
4. Terminal (normaler User) starten
5. Im Terminal folgende Befehle ausführen:

* `wget https://raw.githubusercontent.com/carstenlucke/gish/main/scripts/preparevm.sh`
* `chmod 755 preparevm.sh`
* `./preparevm.sh`

6. Skriptausführung verfolgen. An den meisten Stellen muss man einfach nur die Standardvorschläge mit ENTER bestätigen
7. Browser in Kali starten und `localhost` aufrufen
8. Die Startseite sollte *Guestbook* und *mutillidae* als Links anzeigen
9. Den Link für *mutillidae* anklicken. Die Seite "bemängeln", dass die Datenbank nicht installiert ist. Hier dann einfach den ersten Link zum Setup der Datenbank anklicken. Es sollte nun alles installiert werden und dann die Startseite von Mutillidae erscheinen.

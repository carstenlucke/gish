# Anleitung zur Vorbereitung von KALI

1. Kali Linux herunterladen und im Hypervisor Deiner Wahl starten (e.g., VirtualBox, VMWare Fusion, etc.)
2. Kali starten
3. Einloggen

	- Standard-Anmeldedaten: `kali/kali` (bei einigen Images).
	- Hinweis: Während der Installation musst Du höchstwahrscheinlich ein Kennwort für den Benutzer festlegen. Alternativ kann das Kennwort in den Hinweisen zum Image-Download genannt sein.

4. Falls noch nicht geändert, in den Einstellungen / Keyboard das Keyboard-Layout (Reiter *Layout*) auf GERMAN stellen und ENGLISH entfernen
5. Terminal (normaler User) starten
6. Im Terminal folgende Befehle ausführen:

```bash
wget https://raw.githubusercontent.com/carstenlucke/gish/main/scripts/preparevm.sh
chmod 755 preparevm.sh
./preparevm.sh
```

7. Browser in Kali starten und `localhost` aufrufen
8. Die Startseite sollte *Guestbook* und *mutillidae* als Links anzeigen
9. Den Link für *mutillidae* anklicken. Die Seite "bemängeln", dass die Datenbank nicht installiert ist. Hier dann einfach den ersten Link zum Setup der Datenbank anklicken. Es sollte nun alles installiert werden und dann die Startseite von Mutillidae erscheinen.

<img src="assets/img/mutillidae.png" width="500" alt="Fehlermeldung, die darauf hinweist, dass der Datenbankserver unter localhost offline ist">


Hinweis: Falls Du Parallels verwendest oder Probleme bei der Installation von Parallels Tools bzw. mit Kali auf Apple Silicon auftrittest, kann die Datei [Installation Kali und Parallels Tools für GISH auf Apple Silicon Mac.md](docs/Installation%20Kali%20und%20Parallels%20Tools%20für%20GISH%20auf%20Apple%20Silicon%20Mac.md) im Repository zusätzliche Hilfe bieten.
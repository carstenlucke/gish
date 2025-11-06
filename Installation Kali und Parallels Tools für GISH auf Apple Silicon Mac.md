# Schritte / Anleitung

- Herunterladen von Kali Image (Stand 21.11.2024 aktuell = Kali 2024.3)
	- Downloadpage: https://www.kali.org/get-kali/#kali-installer-images
	- Direktlink: https://cdimage.kali.org/kali-2025.3/kali-linux-2025.3-installer-arm64.iso
- Installation von Kali über Erzeugen einer neuen VM und dann das Image für Kali für Apple Silicon Mac verwenden
- Installation von Parallels Tools macht möglicherweise Probleme. 
	- Man darf die CD-Rom nicht durch Anklicken auf dem Desktop mounten, sondern 
```
	sudo mount -o exec /dev/sr0 /media/cdrom
	cd /media/cdrom && sudo ./install
```

- Sehr wahrscheinlich schlägt das zunächst fehl --> es sind benötigte Packages nicht installiert
- Man sollte den folgenden Schritten folgen (ab Zeile 14):

```
# steps needed to get full functionallity out of parallels on a m1 machine
## fresh 2022.3 kali install from arm64 iso

*vm menu, choose actions > install paralell tools*
>this inserts a virt CDrom

sudo mount -o exec /dev/sr0 /media/cdrom
>mount the CD as executable

cd /media/cdrom && sudo ./install
>attempt install, observe the error
>you'll probably need to install some packages - next

sudo apt update 
sudo apt upgrade 
sudo apt auto-remove
>update/upgrade everything and clean up

sudo apt install dkms 
sudo apt install libelf-dev 
sudo reboot
>install needed dependencies, reboot

*vm menu, choose actions > install paralell tools*
>this inserts virt CDrom again

sudo mount -o exec /dev/sr0 /media/cdrom
>mount the CD as executable

cd /media/cdrom && sudo ./install
>attempt install, should be no error
```

Quelle: https://forum.parallels.com/threads/unable-to-install-parallels-tools-in-kali-linux-2021-1-on-m1-parallels-desktop.354896/


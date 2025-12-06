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
                    <div class="gbentry-meta">$author &lt;$email&gt; · $date</div>
                </div>
EOT;
            }
            ?>
                    </div>
                </div>

                <div class="right-column">
                    <div class="demo-section">
                <h3>🔒 XSS Demo Links</h3>
                <div class="demo-links">
                    <a href="clearsession.php" class="demo-link reset">Angriffsdaten löschen</a>
                    <a href="#" onclick="xssAttackName(); return false;" class="demo-link">XSS Username</a>
                    <a href="#" onclick="xssAttackMessageStripOk(); return false;" class="demo-link">XSS Message (strip_tags OK)</a>
                    <a href="#" onclick="xssAttackMessageStripFail(); return false;" class="demo-link">XSS Message (strip_tags FAIL)</a>
                    <a href="#" onclick="xssAttackMessageStripHtmlSCIsSafe(); return false;" class="demo-link">XSS htmlspecialchars is safe</a>
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

        <script>
            function xssAttackName() {
                document.forms.gbform.elements.yourname.value = "<script>alert('xss attack1');<\/script>";
                document.forms.gbform.elements.email.value = "c@mail.com";
                document.forms.gbform.elements.message.value = "Lorem ipsum ...";
            }
            function xssAttackMessageStripOk() {
                document.forms.gbform.elements.yourname.value = "Carsten";
                document.forms.gbform.elements.email.value = "c@mail.com";
                document.forms.gbform.elements.message.value = "Lorem ipsum ... <script>alert('xss attack2');<\/script>";
            }
            function xssAttackMessageStripFail() {
                document.forms.gbform.elements.yourname.value = "Carsten";
                document.forms.gbform.elements.email.value = "c@mail.com";
                document.forms.gbform.elements.message.value = "<u onmouseover=\"javascript:alert('xss attack3');\">Lorem ipsum ... <\/u>";
            }
            function xssAttackMessageStripHtmlSCIsSafe() {
                document.forms.gbform.elements.yourname.value = "Carsten";
                document.forms.gbform.elements.email.value = "c@mail.com  <script>alert('xss attack4');<\/script>";
                document.forms.gbform.elements.message.value = "Lorem ipsum ...";
            }
        </script>
    </body>
</html>
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <title>Guestbook entries</title>
        <link rel="stylesheet" href="/common/css/default.css" type="text/css" media="screen" charset="utf-8"></link>
    </head>
    
    <script type="text/javascript" charset="utf-8">
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
    
    <body>
        <h3>Guestbook (using this implementation: <?php echo get_class($gb); ?>)</h3>

<?php
foreach ($entries as $e) {
    $msg = strip_tags($e->getMessage(), '<b><p><u><i>'); // <u onmouseover="javascrip:alert('xss attack');">Lorem ipsum ...</u>
    $author = $e->getAuthor(); // <script>alert('xss attack');</script>
    $email = htmlspecialchars($e->getEmail());
    $date = htmlspecialchars($e->getEntryDate());
    echo <<<EOT
<div class="gbentry">
    <p>$msg</p>
    <p><em>$author &lt;$email&gt;, $date</em></p>
</div>
EOT;
}
?>
        <div>
            <p>
                <a href="clearsession.php">Angriffsdaten l&ouml;schen</a> | 
                <a href="#" onclick="xssAttackName()">XSS Username</a> | 
                <a href="#" onclick="xssAttackMessageStripOk()">XSS Message strip_tags ok</a> | 
                <a href="#" onclick="xssAttackMessageStripFail()">XSS Message strip_tags fail</a> | 
                <a href="#" onclick="xssAttackMessageStripHtmlSCIsSafe()">XSS htmlspecialchars is safe</a>
            </p>
            <p><strong>Add entry:</strong></p>
            <form action="add.php" method="post" name="gbform" accept-charset="utf-8">
                <p><label for="yourname">Name <em>(unsafe)</em>: </label>
                <input type="text" size="50" name="yourname" value="" id="yourname" maxlength="255" /></p>
                
                <p><label for="yourname">Email <em>(htmlspecialchars)</em>: </label>
                <input type="text" size="50" name="email" value="" id="email" maxlength="255" /></p>
                
                <p><label for="yourname">Message <em>(strip_tags($msg, '&lt;b&gt;&lt;p&gt;&lt;u&gt;&lt;i&gt;'))</em>: </label>
                <textarea name="message" cols="50" rows="5" id="message"></textarea></p>
                
                <p><input type="submit" value="Submit &rarr;"> <input type="reset" value="Reset &rarr;"></p>
                
            </form>
        </div>
    </body>
</html>
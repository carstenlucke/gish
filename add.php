<?php
error_reporting(E_ALL);

// add the class-name of your Guestbook implementation 
$GB_CLASS_IMPL = 'GuestbookDemoImpl';
require_once $GB_CLASS_IMPL . '.php';

require_once 'Guestbook.php';
require_once 'GuestbookEntry.php';

function addEntry(Guestbook $gb, GuestbookEntry $entry) {
    $gb->addEntry($entry);
}

if (
        isset($_POST['yourname']) 
            && isset($_POST['email']) 
            && isset($_POST['message']) 
) {
    
    $entry = new GuestbookEntry(
            $_POST['message'],
            $_POST['yourname'],
            $_POST['email'],
            date('r'));
    
    $gb = new $GB_CLASS_IMPL;
    $entries = addEntry($gb, $entry);
    
}

// redirect to the index page
header('Location: index.php');

?>
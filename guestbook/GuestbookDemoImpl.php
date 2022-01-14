<?php
require_once 'Guestbook.php';
require_once 'GuestbookEntry.php';

session_start();

class GuestbookDemoImpl implements Guestbook {
    
    private $staticEntries;
    
    public function __construct() {
        $this->staticEntries = array(
              new GuestbookEntry('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. ', 'Freak', 'qu@ts.ch', 'Tue, 18 Mar 2008 15:54:40 +0100'),
              new GuestbookEntry('Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. ', 'Donna', 'terminatrix@mymail.tld', 'Tue, 18 Mar 2008 12:54:40 +0100'),
              new GuestbookEntry('Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'max', 'max@mailer.tld', 'Tue, 18 Mar 2008 11:54:40 +0100')
        );
    }
    
    /**
     * Returns all entries currently existing in the guestbook.
     * 
     * @return array an array of GuestbookEntry objects
     */
    public function getAllEntries() {
        $sessionEntries = array();
        if (isset($_SESSION['gbentries'])) {
            $sessionEntries = $_SESSION['gbentries'];
        }
        
        return array_merge($sessionEntries, $this->staticEntries);
    }
    
    /**
     * Add an entry to the guestbook
     * 
     * @param GuestbookEntry the entry to be added
     * @return void
     */
    public function addEntry(GuestbookEntry $entry) {
        if (! isset($_SESSION['gbentries'])) {
            $_SESSION['gbentries'] = array();
        }
        array_unshift($_SESSION['gbentries'], $entry);
    }
    
}

?>
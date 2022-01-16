<?php
interface Guestbook {
    
    /**
     * Returns all entries currently existing in the guestbook.
     * 
     * @return array an array of GuestbookEntry objects
     */
    public function getAllEntries();
    
    /**
     * Add an entry to the guestbook
     * 
     * @param GuestbookEntry the entry to be added
     * @return void
     */
    public function addEntry(GuestbookEntry $entry);
}
?>
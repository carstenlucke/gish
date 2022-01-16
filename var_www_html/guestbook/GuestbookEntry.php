<?php
class GuestbookEntry {
    
    private $message;
    private $author;
    private $email;
    private $date;
    
    /**
     * 
     */
    public function __construct($message, $author, $email, $date) {
        $this->message = $message;
        $this->author  = $author;
        $this->email   = $email;
        $this->date    = $date;
    }
    
    public function getMessage() {
        return $this->message;
    }
    
    public function getAuthor() {
        return $this->author;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    /**
     * @return
     *      RFC 2822 formatted date (Example: "Tue, 18 Mar 2008 16:00:40 +0100")
     */
    public function getEntryDate() {
        return $this->date;
    }
    
}
?>
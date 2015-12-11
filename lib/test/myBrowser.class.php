 <?php

class myBrowser extends sfBrowser {
    public function  __construct() {
        parent::__construct($hostname, $remote, $options);
    }
    
 }
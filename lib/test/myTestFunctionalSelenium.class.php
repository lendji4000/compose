  

  <?php
//SF_ROOT_DIR/lib/test/myTestFunctionalSelenium.class.php
  class myTestFunctionalSelenium extends sfTestFunctional {

    /**
     * @return mySelenium
     */  
    public function getSelenium() {
      //$browser = $this->getBrowser();
        $browser = $this->browser;
      if (!$browser instanceof myBrowserSelenium) {
        throw new Exception("Your browser instance is not myBrowserSelenium");
      }
      return $this->browser->getSelenium();
    }
}

  
  <?php
//SF_ROOT_DIR/lib/test/myBrowserFactory.class.php
  class myBrowserFactory {

    public static function createBrowser() {
      $b = new myBrowser(sfConfig::get('app_domain'));
      return $b;
    }
    public static function createBrowserSelenium() {
      $b = new myBrowserSelenium(
                      sfConfig::get('app_domain'),
                      null,
                      array(),
                      array(  "browserType" => "*firefox" , 'browserUrl' => 'http://google.fr')
      );
      return $b;
    }

    public static function createFunctionalTestBrowser() {
      $b = new myTestFunctional(self::createBrowser());
      return $b;
    }

    public static function createFunctionalTestBrowserSelenium() {
      $b = new myTestFunctionalSelenium(self::createBrowserSelenium());
      $b->setTester('selenium', 'mySeleniumTester');
      return $b;
    }

}
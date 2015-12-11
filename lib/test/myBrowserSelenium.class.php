 
  <?php
 //SF_ROOT_DIR/lib/test/myBrowserSelenium.class.php
  class myBrowserSelenium extends myBrowser {

    protected $selenium;
    protected $sessionId = null;
    protected $hostname='localhost', $remote=null, $options=array();
    public function __construct($hostname = null, $remote = null,
       $options = array(), $seleniumOptions = array()) {
      if (strpos($hostname, 'http://') === false) {
        $seleniumHostName = 'http://' . $hostname;
      }
      $seleniumOptions = array_merge(array(
                  'browserType' => '*safari home/eisge/.PlayOnLinux/wineprefix/Safari" wine C:\\windows\\command\\start.exe /Unix /home/eisge/.PlayOnLinux/wineprefix/Safari/dosdevices/c:/users/eisge/Start\ Menu/Programs/Safari.lnk ',
                  'hostname' => $seleniumHostName,
                  'captureNetworkTraffic' => true
                      ), $seleniumOptions);

      $this->initSelenium($seleniumOptions);
//      parent::__construct($seleniumHostName, $remote, $options);
    }

    protected function initSelenium($seleniumOptions = array()) {
      $this->selenium = new mySelenium(
                      $seleniumOptions['browserType'],
                      $seleniumOptions['browserUrl']
      );

      $this->selenium->start();
    }

    public function start() {
      $this->sessionId = $this->selenium->start();
      return $this;
    }

    public function stop() {
      $this->sessionId = null;
      $this->selenium->stop();

      return $this;
    }

    public function getSessionId() {
      return $this->sessionId;
    }
    
    public function __destruct() {
      $this->stop();
    }

    public function getSelenium() {
      return $this->selenium;
    }
}

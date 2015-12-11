

<?php
//SF_ROOT_DIR/lib/test/mySeleniumTester.class.php
class mySeleniumTester extends sfTester {

    /** @var mySelenium */
    protected $selenium;

    public function __construct(myTestFunctionalSelenium $browser, $tester) {
        parent::__construct($browser, $tester);
        $this->selenium = $this->browser->getSelenium();
        $this->selenium->setDoCommandHook($this, "doCommandFailed");
    }

    /**
     * Prepares the tester.
     */
    public function prepare() {

    }

    /**
     * Initializes the tester.
     */
    public function initialize() {

    }

    public function __call($method, $arguments) {
        if (method_exists($this->selenium, $method)) {
            call_user_func_array(array($this->selenium, $method), $arguments);

            return $this->getObjectToReturn();
        } else {
            return parent::__call($method, $arguments);
        }
    }

    /**
     * Used internally, do not call this method directly
     */
    public function doCommandFailed($response, $command, $args) {
        if ($command != 'waitForPageToLoad' && strpos($response, 'OK') !== 0) {
            $out = $command . ': ' . $response . "\n";

            if (count($args) > 0) {
                foreach ($args as $arg) {
                    $out .= '    ' . $arg . "\n";
                }
            }

            $this->tester->fail($out);
        }
    }
}

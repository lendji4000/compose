<?php
include 'selenium/Testing_Selenium.class.php';
class mySelenium extends Testing_Selenium {

    protected $response;
    protected $doCommandCallbackObject = null;
    protected $doCommandCallbackMethod = null;


    protected function doCommand($verb, $args = array()) {
        $this->response = null;

        $url = sprintf('http://%s:%s/selenium-server/driver/?cmd=%s', $this->host, $this->port, urlencode($verb));

        for ($i = 0; $i < count($args); $i++) {
            $argNum = strval($i + 1);
            $url .= sprintf('&%s=%s', $argNum, urlencode(trim($args[$i])));
        }

        if (isset($this->sessionId)) {
            $url .= sprintf('&%s=%s', 'sessionId', $this->sessionId);
        }

        if (!$handle = fopen($url, 'r')) {
            throw new Testing_Selenium_Exception(
            'Cannot connected to Selenium RC Server'.$url
            );
        }

        stream_set_blocking($handle, false);
        $response = stream_get_contents($handle);

        fclose($handle);

        if ($this->doCommandCallbackObject != null) {
            call_user_func_array(array(
                    $this->doCommandCallbackObject,
                    $this->doCommandCallbackMethod
                    ), array($response, $verb, $args));
        }
        $this->response = $response;
        return $response;
    }

    /**
     * A short hand method for type(), accept array as parameter
     * @param Array $fieldList
     */
    public function typeMany($fieldList) {
        foreach ($fieldList as $k => $v) {
            $this->type($k, $v);
        }
    }

    public function clickAndWait($locator, $timeout = 8000) {
        $this->click($locator);
        $this->waitForPageToLoad($timeout);
    }

    public function setDoCommandHook(&$object, $method) {
        if (!is_object($object)) {
            throw new Exception('Callback object must be an object instance');
        }
        $this->doCommandCallbackObject = $object;
        $this->doCommandCallbackMethod = $method;
    }

    public function getResponse() {
        return $this->response;
    }
}

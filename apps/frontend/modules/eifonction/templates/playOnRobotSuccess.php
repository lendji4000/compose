
<?php
//SF_ROOT_DIR/test/functional/frontend/homeTest.php
//include(dirname(__FILE__) . '/../../bootstrap/functional.php');

//create an instance of functional test browser
$b = myBrowserFactory::createFunctionalTestBrowserSelenium();
//$b=new Testing_Selenium('*firefox','http://www.google.fr','localhost','4444');
//$b->start();
//$b->open('http://www.google.fr');
//echo $b->sessionId;
//$b->close();
//$b->stop();
//        ->info('Open url');

$b->with('selenium')->begin()->setSpeed(5)
        ->info('Open url')
        ->open('http://thecodecentral.com')
        ->info('Click a link')
        ->clickAndWait("link=Github")->info("click reussi")
        ->isTextPresent('thecodecentral')->info("element retrouvé")
        //->isTextPresent('glob:wildcard test*')
        //->isElementPresent('some selector, check documentation')
        //->isElementPresent('//input[@id="myButton"]')
//        ->typeMany(array(
//        'name' => 'something',
//        'url' => 'something',
//))
->info("fin phase1");
$b->info("debut phase 2");
        $b->getSelenium()->clickAndWait("link=Features");
        $b->info("clic sur le lien features");
        $b->getSelenium()->clickAndWait("link=Blog");
        $b->info("link=Blog");
        $b->getSelenium()->waitForPageToLoad(15000);
        $b->getSelenium()->captureEntirePageScreenshot("/home/capturesKalifast/test4.png", "");
//can you call sleep(second), which will pause the execution
//of the script by x seconds, very useful for testing Ajax request
//->sleep(10)


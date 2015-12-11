
<?php
//SF_ROOT_DIR/test/functional/frontend/homeTest.php
//include(dirname(__FILE__) . '/../../bootstrap/functional.php');

//create an instance of functional test browser
//$b = myBrowserFactory::createFunctionalTestBrowserSelenium();


//$b->with('selenium')->begin()->setSpeed(5)
//        ->info('Open url')
//        ->open('http://google.fr')
//        ->info('Click a link')
//        ->clickAndWait("link=Github")->info("click reussi")
//        ->isTextPresent('thecodecentral')->info("element retrouvé");
        //->isTextPresent('glob:wildcard test*')
        //->isElementPresent('some selector, check documentation')
        //->isElementPresent('//input[@id="myButton"]')
//        ->typeMany(array(
//        'name' => 'something',
//        'url' => 'something',
//))
//->info("fin phase1");
//$b->info("debut phase 2");
//        $b->getSelenium()->clickAndWait("link=Features");
//        $b->info("clic sur le lien features");
//        $b->getSelenium()->clickAndWait("link=Blog");
//        $b->info("link=Blog");
//        $b->getSelenium()->waitForPageToLoad(15000);
//        $b->getSelenium()->captureEntirePageScreenshot("/home/capturesKalifast/test4.png", "");
//can you call sleep(second), which will pause the execution
//of the script by x seconds, very useful for testing Ajax request
//->sleep(10)
//$b->shutdown();
//exec('java -jar selenium-server-standalone-2.15.0 stop ', $stoping_selenium);
//  echo $stoping_selenium;
  //http://localhost:4444/selenium-server/driver/?cmd=shutDownSeleniumServer
        ?>
<?php  
//http://eifast.com:8080/frontend_dev.php/eifonction/genererXSL/1/6/1/7.xml
//http://kalifast.com:8080/frontend_dev.php/eifonction/generateXML.xml?id_fonction=19&amp;id_profil=1
//echo Doctrine_Core::getTable('EiFonction')->generateXMLForPHP(19,1,$sf_request);
$cobj1=curl_init("http://eifast.com:8080/frontend_dev.php/eifonction/genererXSL/1/6/1/7.xml"); 
$cobj2=curl_init("http://kalifast.com:8080/frontend_dev.php/eifonction/generateXML.xml?id_fonction=19&id_profil=1"); 
//$dom=new DOMDocument();
//       $dom->loadXML(Doctrine_Core::getTable('EiFonction')->generateXMLForPHP(19,1,$sf_request));

//        if($cobj){ // Si la session est bien créée
//            
//        }
        curl_setopt($cobj1,CURLOPT_RETURNTRANSFER,1); //définition des options
        curl_setopt($cobj2,CURLOPT_RETURNTRANSFER,1); //définition des options
        $xsl_doc=curl_exec($cobj1); //execution de la requete curl
        $xsl_doc2=curl_exec($cobj2); //execution de la requete curl
        curl_close($cobj1); //liberation des ressources
        curl_close($cobj2); //liberation des ressources
        $dom=new DOMDocument();
        $dom->loadXML($xsl_doc);
        $dom->formatOutput = true;
        $dom->normalizeDocument();
        $dom2=new DOMDocument();
        $dom2->loadXML($xsl_doc2);
        $dom2->formatOutput = true;
        $dom2->normalizeDocument();
         $xmlfile=$dom2->saveXML();
// Afficher le document XML 
//        echo $dom->save('test1.xml');
//        echo $dom2->save('test2.xml');
 //load the xml file (and test first if it exists)
 $dom_object = new DomDocument();
 //if (!file_exists($xml_file)) exit('Erreur d ouverture du fichier xml');
 $dom_object->loadXML($xmlfile);
 // create dom object for the XSL stylesheet and configure the transformer
 $xsl_obj = new DomDocument();
 //if (!file_exists($xsl_file)) exit('Erreur d ouverture du fichier xsl');
 $xsl_obj->loadXML($dom->saveXML());
 $proc = new XSLTProcessor();
 $proc->importStyleSheet($xsl_obj); // attach the xsl rules
 $html_fragment = $proc->transformToXML($dom_object);
 $html_fragment->saveXML();
echo  $html_fragment;

?>
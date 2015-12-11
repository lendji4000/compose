<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<?php if(isset($eiScenario) ):?>
<?php 
//echo '<?xml-stylesheet type="text/xml" href="'.$sf_request->getHost().$sf_request->getScriptName().
//        '/eiscenario/'.$eiScenario->getProjectId().'/'.$eiScenario->getProjectRef().'/'.
//        $eiScenario->getId().'/5/5/generateXSL.xml"
//            '; 
?> 
<?php include_partial('eiversion/downloadJDT', array("eiVersion" => $eiVersion)); ?>
<?php endif; ?>
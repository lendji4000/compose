<?php 
switch ($sf_request->getParameter('module')):
    case 'eiprojet' : 
        include_component('eiprojet','sideBarHeaderObject'); 
        break;
    case 'bugContext':
        include_component('eisubject','sideBarHeaderObject'); 
        break;
    case 'eicampaign':
            include_component('eicampaign','sideBarHeaderObject'); 
        break;
    case 'eicampaignexecution':
    case 'eicampaigngraph':
            include_component('eicampaign','sideBarHeaderObject'); 
        break;
    case 'eidataset':
        include_component('eiscenario','sideBarHeaderObject'); 
        break;
    case 'eidelivery': 
        include_component('eidelivery','sideBarHeaderObject'); 
        break;
    case 'eideliverystate': 
        include_component('eidelivery','sideBarHeaderObject'); 
        break;
    case 'eideliveryCampaign':
        include_component('eidelivery','sideBarHeaderObject'); 
        break;
    case 'eiiteration':
        include_component('eiiteration','sideBarHeaderObject'); 
        break;
    case 'eifolder':
        break;
    case 'eifunctionnotice':
        break;
    case 'einode':
        break;
    case 'eidatasetstructure':
    case 'eiscenario':
        include_component('eiscenario','sideBarHeaderObject'); 
        break;
        
    case 'eisubject':
        include_component('eisubject','sideBarHeaderObject'); 
        break;
    case 'statistics':
        include_component('statistics','sideBarHeaderObject'); 
        break;
    case 'eisubjectstate':
        include_component('eisubject','sideBarHeaderObject');     
        break; 
    case 'eisubjectdetails':
        include_component('eisubject','sideBarHeaderObject'); 
        break;
    case 'eisubjectsolution':
        include_component('eisubject','sideBarHeaderObject'); 
        break;
    case 'eisubjectmigration':
        include_component('eisubject','sideBarHeaderObject'); 
        break; 
    case 'eisubjecthascampaign':
        include_component('eisubject','sideBarHeaderObject'); 
        break;
    case 'eiversion':
        include_component('eiscenario','sideBarHeaderObject'); 
        break;
    case 'eitestset':
        include_component('eiscenario','sideBarHeaderObject'); 
        break;
    case 'kalfonction':
        include_component('kalfonction','sideBarHeaderObject'); 
        break;
    case 'eifunctionparams':
        include_component('kalfonction','sideBarHeaderObject'); 
        break;
    case 'eiversionnotice':
        include_component('kalfonction','sideBarHeaderObject'); 
        break;
    case 'functionCampaigns':
        include_component('kalfonction','sideBarHeaderObject'); 
        break;
    case 'subjectfunction':  
        include_component('eisubject','sideBarHeaderObject');
        break;
    case 'eiuser':
        include_component('eiuser','sideBarHeaderObject'); 
        break;
    case 'eiuserparam':
        include_component('eiuser','sideBarHeaderObject'); 
        break;
    case 'eiuserprofileparam':
        include_component('eiuser','sideBarHeaderObject'); 
        break;
    case 'eiresources':
        include_partial('eiresources/sideBarHeaderObject'); 
        break;
    default :
        break;
endswitch; 
?>
 
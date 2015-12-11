<?php

switch ($sf_request->getParameter('module')):
    case 'eiprojet' :
        include_component('eiprojet', 'sideBarProject');
        break;
    case 'bugContext':
        include_component('eisubject', 'sideBarSubject');
        break;
    case 'eicampaign':
        include_component('eicampaign', 'sideBarCampaign');
        break;
    case 'eicampaignexecution':
    case 'eicampaigngraph':
        include_component('eicampaign', 'sideBarCampaign');
        break;
    case 'eidataset':
        include_component('eiversion', 'sideBarVersion');
        break;
    case 'eidelivery':
        include_component('eidelivery', 'sideBarDelivery');
        break;
    case 'eideliverystate':
        include_component('eidelivery', 'sideBarDelivery');
        break;
    case 'eideliveryCampaign':
        include_component('eidelivery', 'sideBarDelivery');
        break;
    case 'eiiteration':
        include_component('eiiteration', 'sideBarIteration');
        break;
    case 'eifolder':
        break;
    case 'eifunctionnotice':
        break;
    case 'einode':
        break;
    case "eidatasetstructure":
    case 'eiscenario':
        include_component('eiscenario', 'sideBarScenario');
        break;
    break;
    case 'eisubject':
        include_component('eisubject', 'sideBarSubject');
         break;
    case 'statistics':
        include_component('statistics', 'sideBarStats');
         break;    
    case 'eisubjectstate':
        include_component('eisubject', 'sideBarSubject');
        break;
    case 'subjectfunction':
        include_component('eisubject', 'sideBarSubject');
        break;
    case 'eisubjectdetails':
        include_component('eisubject', 'sideBarSubject');
        break;
    case 'eisubjectsolution':
        include_component('eisubject', 'sideBarSubject');
        break;
    case 'eisubjectmigration':
        include_component('eisubject', 'sideBarSubject');
        break;
    case 'eisubjecthascampaign':
        include_component('eisubject', 'sideBarSubject');
        break;
    case 'eiversion':
        include_component('eiversion', 'sideBarVersion');
        break;
    case 'eitestset':
        include_component('eiversion', 'sideBarVersion');
        break;
    case 'kalfonction':
        include_component('kalfonction', 'sideBarFunction');
        break;
    case 'eifunctionparams':
        include_component('kalfonction','sideBarFunction'); 
        break;
    case 'eiversionnotice':
        include_component('kalfonction','sideBarFunction'); 
        break;
    case 'functionCampaigns':
        include_component('kalfonction', 'sideBarFunction');
        break;
    case 'eiuser':
        include_component('eiuser', 'sideBarUser');
        break;
    default :
        break;
endswitch;
?>

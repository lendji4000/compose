<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function ei_icon($type,$size=null,$class=null,$title=null, $moreClasses=null,$content=null)
{
   $icon="fa"; 
    switch ($type):
        case "ei_dashboard" :  $icon.=" fa-home"; break;
        case "ei_user" :  $icon.=" fa-user"; break;
        case "ei_project" :  $icon.=" fa-desktop"; break;
        case "ei_campaign" :  $icon.=" fa-book"; break;
        case "ei_delivery" :  $icon.=" fa-clock-o"; break;
        case "ei_iteration" :  $icon.=" fa-level-up"; break;
        case "ei_subject" : $size=null;$icon=""; $icon.=" icon-wrench-gear6-6";   break;
        case "ei_scenario" :  $icon.=" fa-cogs"; break;
        case "ei_folder" :  $icon.=" fa-folder"; break;
        case "ei_folder_open" :  $icon.=" fa-folder-open"; break;
        case "ei_function" :  $icon.=" fa-cog"; break;
        case "ei_version" :  $icon.=" fa-code-fork"; break;
        case "ei_profile" :  $icon.=" fa-database"; break;
        case "ei_bloc" :  $icon.=' fa-square'; break;
        case "ei_dataset" :  $icon.=" fa-file-text"; break;
        case "ei_dataset_folder" : $icon.=" fa-folder-o" ; break;
        case "ei_notice" :  $icon.=" fa-file-video-o"; break;
        case "ei_language" :  $icon.=" fa-flag"; break;
        case "ei_testset" :  $icon.=" fa-film"; break;
        case "ei_excel" :  $icon.=" fa-dashboard"; break;
        case "ei_bloc_parameter" :  $icon.=" fa-asterisk"; break;
        case "ei_parameter" :  $icon.=" fa-asterisk"; break;
        //Autres icones 
        case "ei_root_folder" :  $icon.=" cus-house"; break;
        case "ei_team" :  $icon.=" fa-users"; break;
        //Actions
        case "ei_properties" :  $icon.=" fa-wrench"; break;
        case "ei_report" :  $icon.=" fa-film"; break;
        case "ei_stats" :  $icon.=" fa-area-chart"; break;
        case "ei_user_settings" :  $icon.=" fa-gears"; break;
        case "ei_copy" :  $icon.=" fa-copy"; break;
        case "ei_refresh" :  $icon.=" fa-refresh"; break;
        case "ei_search" :  $icon.=" fa-search"; break;
        case "ei_edit" :  $icon.=" fa-pencil-square"; break;
        case "ei_show" :  $icon.=" fa-eye"; break;
        case "ei_list" :  $icon.=" fa-list"; break;
        case "ei_add" :  $icon.=" fa-plus"; break;
        case "ei_add_square" :  $icon.=" fa-plus-square"; break;
        case "ei_delete" :  $icon.=" fa-trash-o"; break; 
        case "ei_log_out" :  $icon.=" fa-power-off"; break;
        case "ei_stats" :  $icon.=" fa-line-chart"; break;
        case "ei_resources" :  $icon.=" fa-cubes"; break;
        case "ei_devices" :  $icon.=" fa-tablet"; break;
        case "ei_download" :  $icon.=" fa-download"; break;
        case "ei_unlink" :  $icon.=" fa-unlink"; break;
        case "ei_execution_stack" : $icon.= "fa-tasks"; break;
        case "ei_life_saver" : $icon.= " fa-life-saver"; break;;
        case "ei_mail" : $icon.= " fa-envelope-o"; break;
        case "ei_phone" : $icon.= " fa-phone"; break;
        default :  break;  
        
    endswitch;
    
    if($size !=null) $icon.=" fa-".$size;
    if($moreClasses!=null) $icon.=" ".$moreClasses;
    $params=array();
    $params["class"]=$icon;
    
    if($class !=null) $params["class"]=$icon." ".$class;
    if($title!=null) $params["title"]=$title;
    
    if($content==null) $content="";
    
    return content_tag("i", $content, $params);
}
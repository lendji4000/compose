<?php if(isset($ei_project) && isset($ei_profiles) && isset($migrateFunct)): ?>
<?php 
  $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name,
          'ticket_id' => $migrateFunct['s_package_id'],
          'ticket_ref' => $migrateFunct['s_package_ref'], 
          'function_id' => $migrateFunct['t_obj_id'],
          'function_ref' => $migrateFunct['t_ref_obj'],
     )   ;
  
   
  if(isset($ei_delivery) && $ei_delivery!=null):
      $url_tab['delivery_id']=$ei_delivery->getId();
  endif;
  $resolved_conflicts=isset($resolved_conflicts)?$resolved_conflicts->getRawValue():array();
?>
<div class=" row function_line">
    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 function_line_checkbox" >
        <input  type="checkbox" class ="check_function_for_migration" />
        <input  type="hidden" class ="function_id" value="<?php echo $url_tab['function_id']   ?>" />
        <input  type="hidden" class ="function_ref" value="<?php echo $url_tab['function_ref']   ?>"/>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 function_line_name" >
        <?php echo ei_icon("ei_function") ?>
        <?php $showFunctionContentUri = $url_tab; 
        unset($showFunctionContentUri['ticket_id']);unset($showFunctionContentUri['ticket_ref']); unset($showFunctionContentUri['delivery_id']);
                $showFunctionContentUri['action']='show'; ?>
        <?php $alert_conflict_class=""; ?>
        <?php if($migrateFunct['nb_occurences']>1): $is_conflict=true; ?>
        <input type="hidden" class="conflictOnFunction" value="1" />
        <?php $alert_conflict_class="alert alert-warning alertConflictClass" ?>
        <?php endif; ?>
        <a href="<?php echo url_for2('showFunctionContent',$showFunctionContentUri  )?>" target="_blank">
            <span class="<?php echo  $alert_conflict_class ?> "> 
                <?php  echo   MyFunction::troncatedText($migrateFunct['t_name'], 30)  ?> 
            </span>  
        </a>   
        
    </div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 function_line_package" >
         <?php if(isset($is_conflict) && $is_conflict): ?>
        <!--On vérifie si le conflit  a déjà été résolu -->
        <?php if(count($resolved_conflicts)>0 && array_key_exists($url_tab['function_id'].'_'.$url_tab['function_ref'].'_'.$url_tab['delivery_id'], $resolved_conflicts)): 
            $resolved_conflicts_item=$resolved_conflicts[$url_tab['function_id'].'_'.$url_tab['function_ref'].'_'.$url_tab['delivery_id']]['profile'];
            $resolved_package_id=$resolved_conflicts[$url_tab['function_id'].'_'.$url_tab['function_ref'].'_'.$url_tab['delivery_id']]['package_id'];
            $resolved_package_ref=$resolved_conflicts[$url_tab['function_id'].'_'.$url_tab['function_ref'].'_'.$url_tab['delivery_id']]['package_ref']; 
            
        endif; ?>
        <?php if(isset($migrateFunctsWithoutCount) && count($migrateFunctsWithoutCount)>0):?>
        <select class="form-control choosePackageForMigrationWhenConflict">
            <option>Choose correct package</option> 
            <?php $t_id_val=0; $t_ref_val=0 ?>
          <?php  foreach($migrateFunctsWithoutCount as $mfwc):
            if($mfwc['t_id']==$migrateFunct['t_id']): $uriItem=$url_tab; 
            unset($uriItem['profile_id']);unset($uriItem['profile_ref']); unset($uriItem['profile_name']);
            $uriItem['ticket_id']=$mfwc['sc_ticket_id'];
            $uriItem['ticket_ref']=$mfwc['sc_ticket_ref']?>
            <option itemref="<?php echo url_for2('getProfileForTicket',$uriItem) ?>" 
                <?php if(isset($resolved_package_id) && $mfwc['sc_ticket_id']==$resolved_package_id && $mfwc['sc_ticket_ref']==$resolved_package_ref ):
                    $t_id_val=$resolved_package_id;$t_ref_val=$resolved_package_ref;
                    echo "selected=selected"; 
                endif; ?>
                    itemid="<?php echo $mfwc['sc_ticket_id']?>" itemtype="<?php echo $mfwc['sc_ticket_ref']?>">
                <?php echo $mfwc['et_name']; ?>
            </option>
            <?php endif;
           endforeach;?>
        
            <input  type="hidden" class ="ticket_id" value="<?php echo $t_id_val ?>" />
            <input  type="hidden" class ="ticket_ref" value="<?php echo $t_ref_val ?>" />
        </select>
        <?php endif; ?>
        <?php else :  ?>
        <a href="#">
            <?php echo $migrateFunct['et_name'] ?> 
            <input  type="hidden" class ="ticket_id" value="<?php echo $url_tab['ticket_id']   ?>" />
            <input  type="hidden" class ="ticket_ref" value="<?php echo $url_tab['ticket_ref']   ?>"/>
        </a>
         <?php endif; ?>
        <?php $subjects_list_uri=$url_tab;  unset($subjects_list_uri['ticket_id']) ; unset($subjects_list_uri['ticket_ref']); 
        unset($subjects_list_uri['function_id']); unset($subjects_list_uri['function_ref']);
        $subjects_list_uri['contextRequest']="interventionLink" ; $subjects_list_uri['is_ajax_request']=true;  ?>
        <a class="btn  btn-link loadInterventionModal" itemref="<?php echo url_for2('subjects_list',$subjects_list_uri) ?>" href="#changeInterventionOnMigrationModal"  data-toggle="modal">
            <?php echo ei_icon('ei_edit') ?>
            <input  type="hidden" class ="script_id" value="<?php echo $migrateFunct['sc_script_id'] ?>" />
        </a>
    </div>
    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 " >
        <?php if(isset($is_conflict) && $is_conflict && !isset($resolved_package_id)): ?>
        <div class="function_line_profiles">
            Conflicts detected on function: <a href="#"> Solve them?</a>
        </div> 
        <?php else: ?>
        <?php if(isset($resolved_package_id)):
        $url_tab['ticket_id']=$resolved_package_id;
            $url_tab['ticket_ref']=$resolved_package_ref; endif; ?>
        <?php $profileForTicketUri=$url_tab; 
        unset($profileForTicketUri['profile_id']);unset($profileForTicketUri['profile_ref']); unset($profileForTicketUri['profile_name']);
        $profileForTicketUri['ei_profiles']=$ei_profiles;
        $profileForTicketUri['scriptProfiles']=$scriptProfiles; 
        $profileForTicketUri['resolved_conflicts_item']=(isset($resolved_conflicts_item) ?$resolved_conflicts_item:array());
        include_partial('kalfonction/profilesForTicket',$profileForTicketUri  )  ?> 
        <?php endif; ?>
    </div>
     
</div> 
<?php endif; ?> 
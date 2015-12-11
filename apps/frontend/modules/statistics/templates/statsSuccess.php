<?php $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref);

    ?>
<div class="panel panel-default eiPanel" id="EiGeneralStatsPanel">
    <div class="panel-heading">
        <h2><?php echo ei_icon('ei_subject') ?> Interventions progress </h2>  
    </div>
<div class="panel-body table-responsive" > 
        <!--<i class="fa fa-4x fa-spin   fa-spinner " id="EiGeneralStatsLoader"></i>-->
        <table class="table table-striped table-bordered bootstrap-datatable  dataTable " id="EiGeneralStatsTable"  >
            <thead>
            <tr>
                <th style="width: 10%"></th>
                <th>
                    <?php if(isset($bugs_states) && count($bugs_states)>0): $totalBug=0; ?>
                    <?php foreach($bugs_states  as $state): $totalBug+= $tabStateCount[$state->getId()] ; ?>
                    <?php $bgUri=$url_tab; $bgUri['state']=array($state->getId()) ?>
                    <a href="<?php echo url_for2("subjects_list",$bgUri) ?>" style="text-decoration:none">
                        <span class="label " style="<?php echo "margin-left:5px; margin-right:5px; width: 35% ; background-color:".$state->getColorCode() ?>">
                         <?php  echo  $state.' ( '.$tabStateCount[$state->getId()].' ) '?>  
                        </span> 
                    </a>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </th>
                
                <th>
                    <a href="<?php echo url_for2("subjects_list",$url_tab) ?>" style="text-decoration:none">
                        <?php echo "Total ( ".$totalBug." )"; ?> 
                     </a>
                </th>
            </tr>
            </thead>
            <tbody> 
                <?php if(isset($ei_project_users) && count($ei_project_users)>0): ?>
                <?php $user_bugs=$user_bugs->getRawValue() ;           $tabUserCount=$tabUserCount->getRawValue(); ?>
                <?php foreach ($ei_project_users as $guard_user):   ?> 
                <tr>
                    <td style="width: 5%">
                        <?php echo ei_icon("ei_user") ?> <?php echo $guard_user->getUsername() ?>
                    </td>
                    <td> 
                        <div class="progress">
                    <?php foreach($bugs_states  as $stateKey => $state): ?>
                    <?php if(array_key_exists($state->getId().$guard_user->getId(), $user_bugs)):  
                        $item=$user_bugs[$state->getId().$guard_user->getId()];  ?>
                            <?php $perct= ($item['nbBugs']/$tabUserCount[$guard_user->getId()])*100; ?>
                            <div class="progress-bar" style="<?php echo "width:".((isset($tabUserCount) && $tabUserCount[$guard_user->getId()]!=0)?$perct:0)."%; background-color:".$item['st_color_code'] ?>">
                                <?php $bgUri=$url_tab; $bgUri['assignment']=$guard_user->getUsername(); $bgUri["state"]=array($stateKey=> $state->getId()) ?>
                                <a href="<?php echo url_for2("subjects_list",$bgUri) ?>" style="color: white ; text-decoration:none"> <?php echo $item['nbBugs'] ?> </a>
                            </div> 
                        
                    <?php endif; endforeach; ?>
                            
                            </div> 
                        </td>
                        <td>
                            <?php $bgUserUri=$url_tab; $bgUserUri['assignment']=$guard_user->getUsername()?>
                         <a href="<?php echo url_for2("subjects_list",$bgUserUri) ?>" style="text-decoration:none">
                            <?php echo isset($tabUserCount[$guard_user->getId()])?$tabUserCount[$guard_user->getId()]:0 ?>
                         </a>    
                        </td>
                </tr>  
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

 
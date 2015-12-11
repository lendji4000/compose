<?php
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?>    
<div id="corps" class="col-lg-12 col-md-12 col-sm-12 marge-none"> 
    <div class="tabbable tabs-left full-height">
        
        <div class="tab-content full-height bordered scenario-panel">

            <div id="new-open" class="tab-pane active full-height">
                <div id="arbre_scenarios" class="col-lg-3 col-md-5 col-sm-6 full-height no-margin">
                <?php $getRootDiagram=$url_tab; 
                      $getRootDiagram['root_node']=$ei_project->getRootFolder();
                      $getRootDiagram['ei_project']=$ei_project;
                      $getRootDiagram['ei_project']=$ei_profile ?>
                    <?php include_component('einode', 'getRootDiagram',$getRootDiagram); ?>
                </div>
                <div id="action_scenarios" class="col-lg-9 col-md-7 col-sm-6">
                    
                </div>
            </div> 
                 <div class="row tab-pane" id="recent"  >		
                    <div class="col-lg-12">
                        <div class="panel panel-default eiPanel">
                            <div class="panel-heading" data-original-title>
                                <h2 class="title_project">
                                     <?php echo ei_icon('ei_scenario') ?>
                                    <span class="break"></span>
                                    Recent scenarios (<?php echo (isset($recent_scenarios) &&(count($recent_scenarios)>0)?count($recent_scenarios):0) ?>)
                                </h2>
                                <div class="panel-actions">   
                                </div>
                            </div>
                            <div class="panel-body table-responsive">
                                <table class="table table-striped  bootstrap-datatable small-font dataTable " id="EiPaginateList">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Created at</th>
                                            <th>Updated at</th>
                                        </tr> 
                                    </thead> 
                                    <tbody>
                                    <?php foreach($recent_scenarios->getRawValue() as $i => $scr): ?>
                                        <?php  $scr = $scr['EiScenario']; ?>
                                    <tr>
                                        <td> 
                                           <?php echo ei_icon('ei_scenario') ?>
                                           <?php $projet_new_eiversion=$url_tab; 
                                                $projet_new_eiversion['ei_scenario_id']=$scr['id'];
                                                $projet_new_eiversion['action']='editVersionWithoutId'; ?>
                                               <?php echo link_to2($scr['nom_scenario'], 
                                                    'projet_new_eiversion', $projet_new_eiversion) 
                                                   ?>
                                        </td>
                                        <td>
                                            <?php echo MyFunction::troncatedText($scr['description'], 100) ?>
                                        </td> 
                                        <td><?php echo date('Y-m-d', strtotime($scr['created_at'])); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($scr['updated_at'])); ?></td>
                                    </tr>      
                                     <?php   endforeach; ?>
                                    </tbody>  
                                </table>            
                            </div>
                        </div>
                    </div><!--/col--> 
                </div><!--/row-->	  
              
        </div>
    </div>
</div>
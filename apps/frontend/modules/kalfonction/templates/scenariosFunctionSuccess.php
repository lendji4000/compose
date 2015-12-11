<?php
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     );
 $nb_scenario=(isset($scenarios_function) &&(count($scenarios_function)>0)?count($scenarios_function):0) ;
 $perc=number_format(((isset($total_scenario) && $total_scenario!=0)?$nb_scenario/$total_scenario:"0")*100,2);
 //Traitement des scénarios de la fonction
 $nb_camp=((isset($ei_function_campaigns) && count($ei_function_campaigns)>0)?count($ei_function_campaigns):0);
?> 
<div class="row">
    
    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4" id="scenariosFunctionStats">    
       <div class="panel panel-default eiPanel" >
            <div class="panel-heading">
                <h2>  
                    <?php echo ei_icon('ei_stats') ?> 
                    Scenarios   
                    <strong>(statistics)</strong>
                </h2>
                <div class="panel-actions"> 
                </div>
            </div> 
            <div class="panel-body table-responsive" id=" ">  
                 <div class="info-box info ei-info-box">
                        <?php echo ei_icon('ei_scenario') ?>
                        <div class="count"><?php echo $perc." % " ?></div>
                        <div class="title"><?php echo $total_scenario." total" ?></div>
                        <div class="desc"><?php echo "Used in ".$perc." % of scenarios  "  ?></div>
                    </div>
                    <hr/>
                    <div class="row">
                        <?php if($nb_scenario==0): ?> <!-- La fonction n'est utilisée dans aucun scénario : on lève un warning-->
                        <div class="alert alert-warning"> 
                            <strong>No scenario! </strong> You need to test this function ...
                        </div> 
                        <?php endif; ?> 
                        <?php if($perc>10): ?> <!-- La fonction est très utilisée : on vérifie s'il existe une campagne de test pour la fonction et on demande à en créer -->
                            <?php if($nb_camp>0): ?>
                            <div class="alert alert-warning"> 
                                <strong>Often used! </strong> You need to play function campaigns for each delivery validation tests ...
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger"> 
                                <strong>Critical! </strong> You need to create a campaign for this function and play her for each delivery validation tests ...
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
            </div>
    </div>
    </div>
    <div class="col-lg-9 col-md-9" id="scenariosFunction"> 
        <div class="panel panel-default eiPanel" >
            <div class="panel-heading">
                <h2>  
                    <?php echo ei_icon('ei_scenario') ?> 
                    Scenarios function
                    <strong>(<?php echo (isset($nb_scenario) &&($nb_scenario>0)?$nb_scenario:0) ?>)</strong>
                </h2>
                <div class="panel-actions"> 
                </div>
            </div> 
            <div class="panel-body table-responsive" id="scenariosFunctionList">  
                <table class="table table-striped bootstrap-datatable  dataTable small-font"  >
                    <thead>
                        <tr>
                            <th>   Occurences  </th>
                            <th>   Scenario  </th>
                            <th>   Version  </th> 
                        </tr> 
                    </thead>   
                    <tbody>  
                        <?php if(isset($nb_scenario) && $nb_scenario>0): ?>
                        <?php foreach($scenarios_function as $scenario_function ): ?>
                        <tr>
                            
                            <td><strong><?php echo $scenario_function['nb_occurences'] ?></strong></td>
                            <td>
                                <?php  $projet_eiscenario_action=$url_tab;
                                    $projet_eiscenario_action['ei_scenario_id']=$scenario_function['s_id'];
                                    $projet_eiscenario_action['action']='edit'; ?>
                                <a  href=" <?php echo url_for2("projet_eiscenario_action", $projet_eiscenario_action);?>"   title="Go to edition panel" target="_blank">  
                                    <?php echo ei_icon('ei_scenario') ?> <?php echo $scenario_function['s_nom_scenario'] ?>
                                </a> 
                            </td>
                            <td>
                                <?php 
                                $projet_edit_eiversion=$url_tab;
                                $projet_edit_eiversion['ei_scenario_id']=$scenario_function['s_id'];
                                $projet_edit_eiversion['action']='edit';
                                $projet_edit_eiversion['ei_version_id']=$scenario_function['v_id'];
                                ?>
                            <a href="<?php  echo url_for2('projet_edit_eiversion', $projet_edit_eiversion); ?>" 
                               title="<?php echo $scenario_function['v_libelle'] ?>" target="_blank" >
                                <?php echo ei_icon('ei_version') ?> <?php echo $scenario_function['v_libelle'] ?>
                            </a>
                            </td>
                        </tr>
                        
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                
            </div>        
        </div>  
              
    </div>
</div>

<?php
$urlParams = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_name' => $profile_name,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref); 
$last_exec_tab=(isset($last_exec) && count($last_exec)>0)?$last_exec[0]:null; 

/* Campagnes de la fonction */
$nb_total_campaigns=(isset($ei_project_campaigns) &&(count($ei_project_campaigns)>0)?count($ei_project_campaigns):0) ;
$nb_ei_occurences_function=(isset($ei_occurences_function) &&(count($ei_occurences_function)>0)?count($ei_occurences_function):0) ;
 $perc=number_format(((isset($nb_total_campaigns) && $nb_total_campaigns!=0)?$nb_ei_occurences_function/$nb_total_campaigns:"0")*100,2);
 
 /* Scénarios */
  $nb_scenario=(isset($scenarios_function) &&(count($scenarios_function)>0)?count($scenarios_function):0) ;
 $perc_scenario=number_format(((isset($total_scenario) && $total_scenario!=0)?$nb_scenario/$total_scenario:"0")*100,2);
 //Traitement des scénarios de la fonction
 $nb_camp=((isset($ei_function_campaigns) && count($ei_function_campaigns)>0)?count($ei_function_campaigns):0);
?>
<div class="row">
    <?php if(isset($ei_tree) && $ei_tree!=null):?>
        <?php if($ei_tree->getPath() !=null):    ?>
            <?php $arrayPath=  json_decode(html_entity_decode($ei_tree->getPath()) ,true);   ?>
             
            <?php if(count($arrayPath)>0):     ?>
    <ol class="breadcrumb">
            <?php foreach ($arrayPath as $path): ?>
        <li>
            <?php if($path['type']=="View"):?>
                <?php echo ei_icon('ei_folder',null,'img_node',"Function folder node" ,"function_folder_node")?>
                <?php  echo  $path['name'] ?> 
            <?php else: ?>
            <?php $funcUri = $urlParams; $funcUri['function_id']=$path['obj_id'];
                $funcUri['function_ref']=$path['ref_obj']; $funcUri['action']='show'; ?>
            <a href="<?php echo url_for2('showFunctionContent',$funcUri  ) ?>">
                <?php echo ei_icon("ei_function")?> <?php echo  $path['name'] ?> 
            </a>
            <?php endif; ?>
        </li>
            <?php endforeach; ?>
    </ol>         
            <?php endif; ?>
        <?php endif;?>
    <?php endif; ?>
</div> 
<?php $mainInfParams=$urlParams; $mainInfParams['kal_function']=$kal_function  ; 
include_partial('mainInf',$mainInfParams); //Inclusion du partiel des informations principales de la fonction ?> 
<div class="panel panel-default eiPanel">
    <div class="panel-heading">
        <h2><?php echo ei_icon('ei_stats') ?> Function statistics</h2>
    </div>
    <div class="panel panel-body">
        <div class="row">
          <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6" > 
        <div class="panel panel-default eiPanel propertiesFunctionStatsPanelTitle"  > 
            <div class="panel-heading">
                <h2><?php echo ei_icon('ei_scenario') ?> Last execution</h2> 
            </div>  	 
        </div>
        <?php if($last_exec_tab!=null): ?>  
            <div class="info-box info-muted ei-info-box"> 
               <?php echo ei_icon("ei_function")?>             
                <?php $text_class="text-muted";
                $text_class=($last_exec_tab['status']=="ko"?"text-danger":$text_class);
                $text_class=($last_exec_tab['status']=="ok"?"text-success":$text_class); ?>
                <div class="count <?php echo $text_class ?>"><?php echo $last_exec_tab['name'] ?></div>
                <div class="title <?php echo $text_class ?>">Duration : <?php echo $last_exec_tab['duree'].'  ms' ?> </div>
                <div class="title <?php echo $text_class ?>">Start at : <?php echo $last_exec_tab['date_debut'] ?> </div>
                <div class="title <?php echo $text_class ?> ">End at : <?php echo $last_exec_tab['date_fin'] ?> </div>
            </div>  
        <?php else : ?>
        <div class="alert alert-warning"> 
            <strong>Never executed! </strong> This function was never played .You need to execute her ...
        </div>
        <?php endif; ?>   
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6"> 
        <div class="panel panel-default eiPanel propertiesFunctionStatsPanelTitle"  > 
            <div class="panel-heading">
                <?php $nbEtapes=0 ?> 
                <?php if(isset($exByStateFunctions) && count($exByStateFunctions)> 0): ?>
                <?php foreach($exByStateFunctions as $ex) : $nbEtapes+=$ex['nbEx']; endforeach ;?>
                <?php endif; ?>
                <h2><?php echo ei_icon('ei_scenario') ?>  Executions (<strong><?php echo $nbEtapes ?></strong>)</h2> 
            </div>   
        </div>
         
        <?php if(isset($exByStateFunctions) && count($exByStateFunctions)> 0): ?> 
        <div class="info-box info-muted ei-info-box"> 
            <?php echo ei_icon("ei_function")?> 
            <?php foreach($exByStateFunctions as $ex) : ?>   
                     
            <div class="title " style="color: <?php echo $ex['color_code'] ?>; border-color: <?php echo $ex['color_code'] ?>">
                <?php echo $ex['name'] ?> : <?php echo ($nbEtapes > 0) ? number_format($ex['nbEx']*100/$nbEtapes, 2):0 ?>%
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="alert alert-warning"> 
            <strong>Warning! </strong> No execution found for this function ...
        </div> 
        <?php endif; ?>
        
         
            
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6" id="campaignsFunctionStats">    
        <div class="panel panel-default eiPanel propertiesFunctionStatsPanelTitle "  > 
            <div class="panel-heading">
                <h2><?php echo ei_icon('ei_campaign') ?> Campaigns (<strong><?php echo $nb_total_campaigns ?></strong>)</h2> 
            </div>   
        </div>
        <div class="info-box info-muted ei-info-box">
            <?php echo ei_icon('ei_campaign') ?>
            <div class="count text-info"><?php echo $perc." % " ?></div>
            <div class="title text-info"><?php echo  $nb_ei_occurences_function.'   /   '.$nb_total_campaigns." total" ?></div>
            <div class="desc text-info"><?php echo "Used in ".$perc." % of campaigns"  ?></div>
        </div> 
    </div>  
        </div>
        <div class="row">
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6" id="scenariosFunctionStats">  
        <div class="panel panel-default eiPanel propertiesFunctionStatsPanelTitle "  > 
            <div class="panel-heading">
                <h2><?php echo ei_icon('ei_scenario') ?> Scénarios</h2> 
            </div>  	 
        </div>
        <div class="info-box info-muted eiPanelHeight2 ei-info-box">
            <?php echo ei_icon('ei_scenario') ?>
            <div class="count text-info"><?php echo $perc_scenario." % " ?></div>
            <div class="title text-info"><?php echo $nb_scenario.' / '.$total_scenario." total" ?></div>
            <div class="desc text-info"><?php echo "Used in ".$perc_scenario." % of scenarios  "  ?></div>
        </div>  
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
        <div class="panel panel-default eiPanel propertiesFunctionStatsPanelTitle"  > 
            <div class="panel-heading">
                <h2><i class="fa fa-text-width"></i> Recommandations </h2> 
            </div>  	 
        </div>
        <div class="row eiPanelHeight2">
            <?php if($nb_scenario==0): ?> <!-- La fonction n'est utilisée dans aucun scénario : on lève un warning-->
            <div class="alert alert-warning "> 
                <strong>No scenario! </strong> You need to test this function ...
            </div> 
            <?php endif; ?> 
            <?php if($perc_scenario>10): ?> <!-- La fonction est très utilisée : on vérifie s'il existe une campagne de test pour la fonction et on demande à en créer -->
                <?php if($nb_camp>0): ?>
                <div class="alert alert-warning"> 
                    <strong>Often used! </strong> You need to play function campaigns for each delivery validation tests ...
                </div>
            <?php else: ?>
                <div class="alert alert-danger"> 
                    <strong>Critical! </strong> You need to create a campaign for this function and play it for each delivery validation tests ...
                </div>
                <?php endif; ?>
            <?php else: ?>
            <div class="alert alert-warning"> 
                    <strong>Attention! </strong> You need to create a campaign for this function  ...
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
    </div>
    
</div>



<!--Fenêtre modale d'ajout d'une fonction à partir de compose-->
<div id="editKalFunctionModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editKalFunctionModalLabel" aria-hidden="true">
    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                 <h3 id="editKalFunctionModalLabel">Edit Function properties</h3> 
                <input class="node_id" type="hidden" name="node_id" />
                <div class="eiLoading">
                    <i class="fa fa-spinner fa-spin fa-4x" ></i>   
                </div>
            </div>
            <div class="modal-body " id="editKalFunctionModalBody">
                
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-sm btn-success pull-right" id="updateKalFunction" type="submit">
                    <i class="fa fa-check"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>
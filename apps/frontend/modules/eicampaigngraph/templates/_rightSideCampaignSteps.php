<!-- Ligne d'une step de campagne dans le menu de droite lors de l'édition du contenu d'une campagne -->
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name);

$argsSc = array_merge($url_tab,array(
    "ei_scenario_id" => "0",
    "action" => 'editVersionWithoutId'
));
?>

<?php if(isset($ei_campaign)  ): ?>
<?php if($ei_campaign!=null && $project_id!=null && $project_ref!=null ) : ?> 
<div class="rightSideStepsListOfCampaignContent  "> 
    
    <?php if(isset($steps) && count($steps) > 0): ?> 
    <div class='col-md-12 col-lg-12 col-sm-12 rightSideStepCheckAll'>
        <div class='col-lg-1 col-md-1 col-sm-1'>
            <h6><input  type='checkbox' class="check_all_steps_for_mult_act" /></h6>
        </div> 
    </div>
    <?php foreach($steps as $step): ?>
    <?php $is_automatizable=$step->getEiCampaignGraphType()->getAutomate();
      $step_id=$step->getId();
      $ei_scenario=$step->getEiScenario();
    ?>
    <div class='col-md-12 col-lg-12 col-sm-12 rightSideStep '>
        <div class='col-lg-1 col-md-1 col-sm-1 '>
            <h6><input  type='checkbox' class="check_step_for_mult_act" value="<?php echo $step->getId() ?>" /></h6>
        </div>
        
        <div class='col-lg-6 col-md-6 col-sm-6'>
            <h6> 
                <?php if(!$is_automatizable): ?> 
                        <?php if($step->getFilename()!=null): ?>
                        <?php $downloadCampaignNodeAttachment=$url_tab;
                        $downloadCampaignNodeAttachment['campaign_id']=$step->getCampaignId(); 
                        $downloadCampaignNodeAttachment['id']=$step_id;  ?>  
                        <?php echo link_to2('<input type="hidden" class="fileInCampaign" value ="'.$step->getFilename().'"/>'.ei_icon('ei_function').$step->getFilename(), 
                                'downloadCampaignNodeAttachment', $downloadCampaignNodeAttachment,
                        array('class'=> 'btn btn-link btn-xs  troncObjName',  'title' => $step->getFilename())) ?>  

                        <?php endif; ?>
                    <?php else: ?> 
                    <?php if ($ei_scenario!=null):?>
                <?php $addStepInContent=$url_tab;
                        $addStepInContent['campaign_id']=$step->getCampaignId(); 
                        $addStepInContent['id']=$step_id;  ?> 
                <a href="<?php echo url_for2('addStepInContent',$addStepInContent) ?>" 
                   class="btn btn-link btn-xs addStepInContent troncObjName" title="<?php echo $ei_scenario ?>">
                    <input type="hidden" class="scenarioInCampaign" value ="<?php echo $ei_scenario->getId() ?>"/>
                      <?php echo ei_icon('ei_scenario') ?>
                     <?php echo $ei_scenario  ?>  
                </a>
                <?php else: ?>
                <?php echo ei_icon('ei_scenario') ?> No scenario
                 <?php endif;?> 
                
                <?php if ($ei_scenario!=null):?> 
                <?php $projet_new_eiversion=$url_tab;
                        $projet_new_eiversion['ei_scenario_id']=$ei_scenario->getId(); 
                        $projet_new_eiversion['action']='editVersionWithoutId';  ?> 
                <a target="_blank" href="<?php echo  url_for2( 'projet_new_eiversion',$projet_new_eiversion ) ?>#">
                    <i class="fa fa-external-link"></i> 
                </a>
                <?php endif; ?>
                   
                   <?php endif; ?>
            </h6>
        </div> 
        <div class='col-lg-5 col-md-5 col-sm-5'>
         <?php if($is_automatizable):

             /** @var EiDataSet $dt */
             $dt = $step->getEiDataSet();

             if( !is_null($dt) && $ei_scenario!=null && $dt->getName()!=""):
                 $addStepInContent=$url_tab;
                 $addStepInContent['campaign_id']=$step->getCampaignId();
                 $addStepInContent['id']=$step_id;

                 $argsSc["ei_scenario_id"] = $ei_scenario != null ? $ei_scenario->getId():"";
                 $path_to_scenario = url_for2("projet_new_eiversion", $argsSc);

                 echo link_to2('<input type="hidden" class="jddInCampaign" value ="'.$dt->getId().'"/>'.ei_icon('ei_dataset','lg').$dt->getEiDataSetTemplate()->getName(),
                        'addStepInContent', $addStepInContent,
                        array('class' => 'btn btn-link btn-xs addStepInContent troncObjName', 'title' => $dt->getEiDataSetTemplate()->getName()));
                 ?>
                 <a href="<?php echo $path_to_scenario; ?>" class="externalDataSetAccessLink" target="_blank" data-id="<?php echo $dt->getEiDataSetTemplateId(); ?>"
                    data-parent="<?php echo $ei_scenario->getId() ?>">
                     <i class='fa fa-external-link'></i>
                 </a>
         <?php else: ?>
                <button class="btn btn-xs btn-link stepLineInContentDataSetTitle" type="button">
                     <?php echo ei_icon('ei_dataset','lg') ?>
                </button>
         <?php endif; ?>
    <?php endif; ?>
        </div> 
    </div>
    <?php endforeach ; ?> 
    <?php endif; ?> 
</div>
<?php endif; ?>
<?php endif; ?>
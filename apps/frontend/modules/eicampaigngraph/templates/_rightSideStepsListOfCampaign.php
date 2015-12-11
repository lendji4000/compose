<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name);   ?>
<!-- Listing des steps d'une campagne dans le menu de droite pour la séléction
lors de l'édition du contenu d'une campagne -->

<!-- Récupération et définition du flag et du statut de la campagne pour la campagne courante-->
<?php
$cr_id=$ei_current_campaign->getId();
$flags = $ei_campaign->getFlagCampaigns();
$state='Blank';
$comment = null;
if (count($flags) > 0): 
    foreach ($flags as $flag):
        if ($flag->getCampaignId() == $cr_id && $flag->getFlagCampaignId() == $ei_campaign->getId()):
            $comment = $flag->getDescription();
            $state=$flag->getState();
        endif;
    endforeach; 
endif;
?>
<div class="rightSideStepsListOfCampaign">
    <div class="col-lg-12 col-md-12 col-sm-12 well well-sm">
       <!-- Campaign  Header -->
        <div class="rightSideStepsListOfCampaignHeader row">
            <div class='col-lg-1 col-md-1 col-sm-1'> 
                <h6>
                    <?php $showCampaignSteps=$url_tab;
                          $showCampaignSteps['campaign_id']=$ei_campaign->getId();  ?>
                    <i class="show_camp_steps fa fa-plus-square " title="Show steps"
                       data-href="<?php echo url_for2('showCampaignSteps', $showCampaignSteps) ?>"  > 
                    </i> 
                </h6> 

            </div>
            <div class='col-lg-7 col-md-7 col-sm-7'>
                <?php if(isset($ei_campaign) && $ei_campaign!=null):  ?>
                <h6> 
                    <strong title="<?php echo  $ei_campaign ?>"><?php echo ei_icon('ei_campaign', 'lg') ?>
                        <?php echo  $ei_campaign   ?>
                    </strong> 
                </h6>
                <?php endif ;?>
            </div>
            <div class='col-lg-2 col-md-2 col-sm-2'>
                <?php if(isset($ei_campaign) && $ei_campaign!=null):  ?>
                <h6>  
                    <?php $commentLink=$url_tab;
                          $commentLink['obj_id']=$ei_campaign->getId();
                          $commentLink['comment']=($comment!=null ? $comment: '');
                          $commentLink['flagType']='EiCampaign'; 
                          include_partial('eiflag/commentLink' , $commentLink) ?> 
                </h6>
                <?php endif ;?>
            </div>
            <div class='col-lg-2 col-md-2 col-sm-2'>
                <?php if(isset($ei_campaign) && $ei_campaign!=null):  ?>
                <h6> 
                    <?php $flagLink=$url_tab;
                          $flagLink['obj_id']=$ei_campaign->getId();
                          $flagLink['state']=$state;
                          $flagLink['flagType']='EiCampaign'; 
                         include_partial('eiflag/flagLink' , $flagLink);?>
                </h6>
                <?php endif ;?>
            </div> 
        </div>
        <!-- Steps list -->
        <div class="rightSideStepsListOfCampaignContent row"> 

        </div> 
    </div> 

</div>
 

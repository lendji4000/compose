<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name);   ?>
<!-- Listing des campagnes d'une livraison dans le menu de droite
lors de l'édition du contenu d'une campagne -->

<!-- Récupération et définition du flag et du statut de la campagne pour la campagne courante-->
<?php
$cr_id=$ei_current_campaign->getId();
$flags = $ei_delivery->getFlagDeliverys() ; //Flags de la livraison
$state='Blank';
$comment = null;
if (count($flags) > 0): 
    foreach ($flags as $flag):
        if ($flag->getCampaignId() == $cr_id && $flag->getDeliveryId() == $ei_delivery->getId()):
            $comment = $flag->getDescription();
            $state=$flag->getState();
        endif;
    endforeach; 
endif;
?>
<div  id="rightSideCampaignsBloc" class="row">
    <div id="rightSideDeliveryBloc" class="col-lg-12 col-md-12 col-sm-12 well well-sm">
        <!-- Delivery Header -->
        <div id="rightSideDeliveryHeader" class="row">
            <div class='col-lg-1 col-md-1 col-sm-1'> 
                <h6>
                    <?php $showDeliveryCampaigns=$url_tab;
                            $showDeliveryCampaigns['delivery_id']=$ei_delivery->getId();
                            $showDeliveryCampaigns['ei_current_campaign']=$ei_current_campaign;?> 
                    <i id="hide_del_camps" title="Show campaigns and bugs" class="fa fa-minus-square"
                         data-href="<?php echo url_for2('showDeliveryCampaigns',$showDeliveryCampaigns) ?>" ></i>
                </h6> 

            </div>
            <div class='col-lg-7 col-md-7 col-sm-7'>
                <?php if(isset($ei_delivery) && $ei_delivery!=null):  ?>
                <h6>
                    <strong title="<?php echo $ei_delivery ?>"> <?php echo ei_icon('ei_delivery') ?> &nbsp;  
                        <?php echo  $ei_delivery  ?>
                    </strong> 
                </h6>
                <?php endif ;?>
            </div>
            <div class='col-lg-2 col-md-2 col-sm-2'>
                <?php if(isset($ei_delivery) && $ei_delivery!=null):  ?>
                <h6>  
                    <?php $commentLink=$url_tab;
                            $commentLink['obj_id']=$ei_delivery->getId();
                            $commentLink['comment']=($comment!=null ? $comment: '');
                            $commentLink['flagType']='EiDelivery'; 
                        include_partial('eiflag/commentLink' ,$commentLink) ?> 
                </h6>
                <?php endif ;?>
            </div>
            <div class='col-lg-2 col-md-2 col-sm-2'>
                <?php if(isset($ei_delivery) && $ei_delivery!=null):  ?>
                <h6> 
                    <?php $flagLink=$url_tab;
                            $flagLink['obj_id']=$ei_delivery->getId();
                            $flagLink['state']=$state;
                            $flagLink['flagType']='EiDelivery';  
                            include_partial('eiflag/flagLink' , $flagLink)  ?> 
                </h6>
                <?php endif ;?>
            </div> 
        </div>
        
        <!-- Delivery Campaigns -->
        <div id="rightSideDeliveryContent" class="row">
            <div class=" " id="campaignsPart">
                <?php if(isset($ei_campaigns) && count($ei_campaigns)>0): ?>
                <?php foreach( $ei_campaigns as $ei_campaign): ?>
                <?php if($ei_campaign->getEiCampaign()->getId()!=$ei_current_campaign->getId()): ?>
                <?php $rightSideStepsListOfCampaign=$url_tab;
                $rightSideStepsListOfCampaign['ei_campaign']=$ei_campaign->getEiCampaign(); 
                $rightSideStepsListOfCampaign['ei_current_campaign']=$ei_current_campaign;  
                include_partial('eicampaigngraph/rightSideStepsListOfCampaign',$rightSideStepsListOfCampaign)  ?> 
                <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                
            </div>
            <div class=" " id="subjectsPart">
                <?php if(isset($ei_subjects) && count($ei_subjects)>0): ?>
                <?php foreach( $ei_subjects as $ei_subject): ?>
                <?php $rightSideSubjectBloc=$url_tab;
                $rightSideSubjectBloc['ei_subject']=$ei_subject; 
                $rightSideSubjectBloc['ei_current_campaign']=$ei_current_campaign;   
                include_partial('eicampaigngraph/rightSideSubjectBloc',$rightSideSubjectBloc)  ?>  
                <?php endforeach; ?>
                <?php endif; ?>
            </div> 
        </div>
    </div> 
</div>


<!-- Listing des campagnes d'un sujet dans le menu de droite
lors de l'édition du contenu d'une campagne -->
<!-- Récupération et définition du flag et du statut de la campagne pour la campagne courante-->
<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?>  

<?php
$cr_id=$ei_current_campaign->getId(); 
$flags = $ei_subject['flagSubjects'] ; //Flags du sujet par rapport à la campagne courante
$state='Blank';
$comment = null;
if (count($flags) > 0): 
    foreach ($flags as $flag):
        if ($flag['fs_campaign_id'] == $cr_id && $flag['fs_subject_id'] == $ei_subject['id']):
            $comment = $flag['fs_description'];
            $state=$flag['fs_state'];
        endif;
    endforeach; 
endif;
?>
    <div class="rightSideSubjectBloc    ">
        <div class="well well-sm col-lg-12 col-md-12 col-sm-12">
           <!-- Subject Header -->
            <div class="rightSideSubjectBlocHeader row ">
                <div class='col-lg-1 col-md-1 col-sm-1'> 
                    <h6>
                        <?php $showSubjectCampaigns=$url_tab  ?>
                        <?php $showSubjectCampaigns['subject_id']=$ei_subject['id']  ?>
                        <i class="<?php echo ((isset($ajax_request) && $ajax_request)? "fa fa-minus-square hide_sub_camps" : "fa fa-plus-square show_sub_camps" ) ?>" 
                           title="Show campaigns" data-href="<?php echo url_for2('showSubjectCampaigns',$showSubjectCampaigns) ?>" ></i>
                    </h6> 

                </div>
                <?php if(isset($ei_subject) && $ei_subject!=null):  ?>
                <div class='col-lg-7 col-md-7 col-sm-7'> 
                    <h6>
                        <strong title="<?php echo  $ei_subject['name'] ?>"><?php echo ei_icon('ei_subject') ?>&nbsp;
                            <?php echo  $ei_subject['name']  ?>
                        </strong>
                    </h6> 
                </div>
                <div class='col-lg-2 col-md-2 col-sm-2'> 
                    <h6>  
                        <?php $commentLink=$url_tab;  
                        $commentLink['obj_id']=$ei_subject['id'];
                        $commentLink['comment']=($comment!=null ? $comment: '');
                        $commentLink['flagType']='EiSubject';  
                        include_partial('eiflag/commentLink' , $commentLink); ?> 
                    </h6> 
                </div>
                <div class='col-lg-2 col-md-2 col-sm-2'> 
                    <h6> 
                        <?php $flagLink=$url_tab;  
                        $flagLink['obj_id']=$ei_subject['id'];
                        $flagLink['state']=$state;
                        $flagLink['flagType']='EiSubject'; 
                        include_partial('eiflag/flagLink' , $flagLink) ?> 
                    </h6> 
                </div> 
                <?php endif ;?>
            </div>
            <!-- Subject Campaigns -->
            <div class="rightSideSubjectBlocContent row ">
                <div  class=" campaignsPart"  >
                    <?php if(isset($ei_campaigns) && count($ei_campaigns)>0): ?>
                    <?php foreach( $ei_campaigns as $ei_campaign): ?>
                    <?php if($ei_campaign->getEiCampaign()->getId()!=$ei_current_campaign->getId()): ?>
                    <?php $rightSideStepsListOfCampaign=$url_tab;  
                        $rightSideStepsListOfCampaign['ei_current_campaign']=$ei_current_campaign;
                        $rightSideStepsListOfCampaign['ei_campaign']=$ei_campaign->getEiCampaign();
                        include_partial('eicampaigngraph/rightSideStepsListOfCampaign',$rightSideStepsListOfCampaign)?> 
                    <?php endif; ?>
                            <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div> 
        </div>
        
    </div>  


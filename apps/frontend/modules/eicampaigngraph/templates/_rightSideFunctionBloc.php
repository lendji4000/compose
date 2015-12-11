<?php $url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name);   ?>
<!-- Listing des sujets d'une fonction
lors de l'édition du contenu d'une campagne -->

    <div class="rightSideFunctionBloc  ">
        <?php if(isset($kal_function) && $kal_function!=null ): ?>
        <div class="well well-sm col-lg-12 col-md-12 col-sm-12">
           <!-- Subject Header -->
            <div class="rightSideFunctionBlocHeader   ">
                <div class='col-lg-1 col-md-1 col-sm-1'> 
                    <h6>
                        <?php $showFunctionContent=$url_tab;
                            $showFunctionContent['function_id']=$kal_function->getFunctionId();
                            $showFunctionContent['function_ref']=$kal_function->getFunctionRef(); 
                            $showFunctionContent['action']='showContent'; ?>  
                        <i id="<?php echo ((isset($ajax_request) && $ajax_request)? 'hide_funct_subj' : 'show_funct_subj' ) ?>" title="Show intervention"  
                             class="<?php echo ((isset($ajax_request) && $ajax_request)? 'fa fa-minus-square' : 'fa fa-plus-square' ) ?> "
                             data-href="<?php echo url_for2('showFunctionContent',$showFunctionContent) ?>" ></i>
                    </h6> 

                </div>
                <div class='col-lg-11 col-md-11 col-sm-11'> 
                    <h6>
                        <strong title="<?php echo $kal_function ?>"><?php echo ei_icon('ei_function', 'lg') ?>&nbsp;
                            <?php echo  $kal_function   ?> 
                        </strong>
                    </h6> 
                </div>
            </div>
            <!-- sujets d'une fonction -->
            <div class="rightSideFunctionBlocContent   ">
                <div class=" " id="campaignsPart">
                    <?php if(isset($ei_campaigns) && count($ei_campaigns)>0): ?>
                    <?php foreach( $ei_campaigns as $ei_campaign): ?>
                    <?php $rightSideStepsListOfCampaign=$url_tab;
                            $rightSideStepsListOfCampaign['ei_campaign']=$ei_campaign->getEiCampaign();
                            $rightSideStepsListOfCampaign['ei_current_campaign']=$ei_current_campaign;
                            include_partial('eicampaigngraph/rightSideStepsListOfCampaign',$rightSideStepsListOfCampaign)?> 
                    <?php endforeach; ?>
                    <?php endif; ?>

                </div> 
                <div class="" id="subjectsPart">
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
        <?php endif; ?>
    </div>  


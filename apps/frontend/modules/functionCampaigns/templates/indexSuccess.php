<?php
$url_params = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name);
$nb_total_campaigns=(isset($ei_project_campaigns) &&(count($ei_project_campaigns)>0)?count($ei_project_campaigns):0) ;
$nb_ei_occurences_function=(isset($ei_occurences_function) &&(count($ei_occurences_function)>0)?count($ei_occurences_function):0) ;
 $perc=number_format(((isset($nb_total_campaigns) && $nb_total_campaigns!=0)?$nb_ei_occurences_function/$nb_total_campaigns:"0")*100,0);
  
?> 
<div class="row"> 
    <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5" id="campaignsFunctionStats">    
        <div class="panel panel-default eiPanel propertiesFunctionStatsPanelTitle"  > 
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
    <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7" id="administrateFunctions">
        <div class="panel panel-default eiPanel">
            <div class="panel-heading">
                <h2>    
                    <?php echo ei_icon('ei_campaign') ?>
                    Function Campaigns (<?php echo (isset($ei_function_campaigns) &&(count($ei_function_campaigns)>0)?count($ei_function_campaigns):0) ?>)
                </h2>
                <div class="panel-actions"> 
                </div>
            </div> 
            <div class="panel-body table-responsive" id="functionCampaignsList">  
                <table class="table table-striped bootstrap-datatable  dataTable small-font"  >
                    <thead>
                        <tr>
                            <th>   Id </th>
                            <th>   Title </th>
                            <th>  Author </th>
                            <th>Description</th> 
                            <th>Updated At</th> 
                            <th >Coverage</th>
                        </tr>
                    </thead>   
                    <tbody> 
                        <?php if (count($ei_function_campaigns)>0): ?>
                        <?php foreach ($ei_function_campaigns as $ei_function_campaign): ?>
                        <?php $campaignLine=$url_params ; 
                              $campaignLine['ei_campaign']=$ei_function_campaign->getEiCampaign(); 
                              include_partial('eicampaign/campaignLine',$campaignLine) ?> 
                        <?php endforeach; ?> 
                        <?php endif; ?> 
                    </tbody>
                </table>
                
                
            </div>
            <div class="panel-footer">  
                <a class="" href="<?php
                echo url_for2('createFunctionCampaign', array(
                    'project_id' => $project_id,
                    'project_ref' => $project_ref,
                    'profile_id' => $profile_id,
                    'profile_ref' => $profile_ref,
                    'profile_name' => $profile_name,
                    'function_id' => $function_id,
                    'function_ref' => $function_ref,
                    'action' => 'new'))
                ?>">
                    <?php echo ei_icon('ei_add' ) ?> Add 
                </a>
            </div>        
        </div>    
    </div>
</div> 
 
  

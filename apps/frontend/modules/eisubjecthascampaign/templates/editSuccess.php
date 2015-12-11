<div class="row">
    <div class="col-lg-3 col-md-3 ">  
      <?php include_partial('eisubject/leftSide',array(
          'project_id' => $project_id,
          'project_ref' => $project_ref,
          'ei_subject' => $ei_subject
      ))?>
    </div>
    <div class="col-lg-9 col-md-9"> 
        <?php include_partial('eisubject/subjectNavBar',array(
            'ei_subject' => $ei_subject,
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'activeItem' => 'Campaigns'
        ))?>
        <?php include_partial('eicampaign/campaignNavBar',array(
            'ei_campaign' => $ei_campaign,
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'activeItem' => 'Properties' 
        ))?>
        <ul class="nav nav-tabs">
            <li class="active ">
                <a href="#" class="">Edit Campaign</a>
            </li>
        </ul>
        <div id="subjectContent">
           <?php include_partial('form', array(
            'form' => $form,
            'project_id' => $project_id,
            'project_ref' => $project_ref,
             'subject_id' => $ei_subject->getId())) ?>
        </div>
        
    </div> 
</div> 


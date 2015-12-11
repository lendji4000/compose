<?php
$urlParams = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_name' => $profile_name,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
); 
?>
<div class="row" id="header2">
    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <?php include_partial('global/header2Object'); ?>     
    </div> 
    <!-- Formulaire de recherche globale -->
<?php if(isset($project_id) && isset($project_ref) && isset($profile_id) && isset($profile_ref) && isset($profile_name)): ?>    
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" id='divGlobalSearchForm'>  
            <form class="form-horizontal navbar-form  " method="post" id='globalSearchForm' 
                  action ="<?php  echo url_for2('bugManagementSearch', $urlParams)  ?>"> 
                <div class="form-group"> 
                        <div class="input-group">
                            <input type="Search" name="reference" placeholder="Search ..." class="form-control" name="input2-group2" id="input2-group2">
                            <span class="input-group-btn">
                                <button class="btn btn-primary" type="submit">
                                    <?php echo ei_icon('ei_search') ?>  </button>
                            </span>
                        </div> 
                </div> 
            </form>  
    </div>
</div> 
<?php endif; ?>
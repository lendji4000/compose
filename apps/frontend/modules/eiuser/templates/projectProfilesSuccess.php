<div class="panel panel-default eiPanel" id="projectProfiles">
    <div class="panel-heading">
        <h2>Project Environments 
            <strong id="nbProjectProfiles"><?php echo '('.(isset($ei_profiles)?count($ei_profiles):0).')' ?></strong>
        </h2>  
    </div> 
    <div class="panel-body"> 
        <?php if(!isset($ei_user_default_profile) || $ei_user_default_profile==null): ?>
        <div class="alert alert-warning" id="projectProfilesListWarning">
            
            <strong>You haven't set your default environment! </strong> Do it for better navigation...
        </div>
        <?php endif; ?>
        <div  id="projectProfilesAlerts"> 
            <a href="#" class="close" data-dismiss="alert">&times;</a> 
            <span></span>
        </div> 
        <table class="table table-striped dataTable bootstrap-datatable " id="projectProfilesList">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Name</th>
                    <th>Created at</th>
                    <th>Updated at</th>
                    <th>Actions </th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($ei_profiles) && count($ei_profiles)>0): ?>
                <?php foreach($ei_profiles as $profil): ?>
                <tr>
                    <td><?php echo $profil->getProfileId().'-'.$profil->getProfileRef() ?></td>
                    <td><?php echo ei_icon("ei_profile") ?> <?php echo $profil ?></td>
                    <td><?php echo $profil->getCreatedAt() ?></td>
                    <td><?php echo $profil->getUpdatedAt() ?></td>
                    <td>
                        <?php if(isset($ei_user_default_profile) && $ei_user_default_profile!=null && $profil->getProfileId()==$ei_user_default_profile->getProfileId()&& 
                                $profil->getProfileRef()==$ei_user_default_profile->getProfileRef()): 
                             $class="btn btn-success btn-sm"; 
                             $id="currentDefaultProfile";
                             $title="Default environment";
                             $text="Default environment";
                             else:
                             $class="btn btn-sm btn-default setDefaultUserProfile"; 
                             $id="";
                             $title="Set profil as your default";
                             $text="Set as default";
                         endif; ?> 
                        <a href="#" itemref="<?php echo url_for2("userProfile",array(
                            "project_id" => $ei_project->getProjectId(),
                            "project_ref" => $ei_project->getRefId(),
                            "profile_id" => $profil->getProfileId(),
                            "profile_ref" => $profil->getProfileRef(),
                            "action" => "setDefaultProfile"
                        )) ?>" class="<?php echo $class ?>" title="<?php echo $title ?>" id="<?php echo $id ?>"><?php echo $text ?>
                        </a> 
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif;?>
            </tbody>
            <tfoot>
                
            </tfoot>
        </table>
    </div> 
        <div class="panel-footer"> 
        </div>    
    </div>  
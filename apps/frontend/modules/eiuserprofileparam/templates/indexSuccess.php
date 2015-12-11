<div class="panel panel-default eiPanel" id="EiUserParams">
    <div class="panel-heading">
        <h2><?php echo ei_icon('ei_list') ?> Overwrite environment parameters</h2>
        <div class="panel-actions"> 
        </div>
    </div>

    <div class="panel-body table-responsive" >
        <!--Listing des paramètres de profil utilisateur --> 
        <!--Fin listing des paramètres utilisateur-->
        <table class="table table-striped table-bordered bootstrap-datatable small-font dataTable ">
            <thead>
              <tr>
                  <th> <?php echo  ei_icon('ei_profile') ?>  Environments</th> 
                  <?php if(isset($ei_profiles) && count($ei_profiles)>0): ?>
                  <?php $tabParams=array(); ?>
                  <?php foreach ($project_params as $projectParam):
                      $tabProjectParams[$projectParam->getParamId()]=$projectParam->getName();
                  endforeach; ?>
                  <?php foreach ($ei_profiles as $ei_profile): ?>
                  <th><?php echo $ei_profile;  ?> 
                      <?php foreach ($ei_profile->getParams() as $profileParam): ?>
                      <?php $tabParams[$tabProjectParams[$profileParam->getName()]][]=
                              array('profile_id'=>$profileParam->getProfileId(),
                                    'profile_ref' => $profileParam->getProfileRef(),
                                    'id' => $profileParam->getId(),
                                    'value' => $profileParam->getValue());  ?>
                      <?php endforeach; ?>
                  </th> 
                  <?php endforeach; ?>
                  <?php endif; ?>    
              </tr>
            </thead>
            <tbody>
              <?php foreach ($tabParams as $name => $tabParam):   ?>
              <tr> 
                  <td><strong><?php  echo $name ;  ?></strong></td> 
                <?php foreach($tabParam as $key => $param):   ?>
                <?php if(isset($usProfParAsArray) && count($usProfParAsArray) >0 && array_key_exists($param['id'], $usProfParAsArray->getRawValue())):
                    include_partial('eiuserprofileparam/userProfileParamCase',
                        array('ei_project' => $ei_project,
                              'ei_user_profile_param' =>array(
                                  'id' => $usProfParAsArray[$param['id']]['id'],
                                  'value' => $usProfParAsArray[$param['id']]['value']  ) 
                    ));                   
                 else:
                      include_partial('eiuserprofileparam/profileParamCase',
                        array('ei_project' => $ei_project,
                              'param' =>$param 
                    ));  endif; ?>  
                <?php endforeach; ?>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table> 
    </div> 
</div>  
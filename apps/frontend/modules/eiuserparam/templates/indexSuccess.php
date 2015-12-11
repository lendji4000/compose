<div class="panel panel-default eiPanel" id="EiUserParams">
                <div class="panel-heading">
                    <h2><?php echo ei_icon('ei_list') ?> User parameters </h2>
                    <div class="panel-actions"> 
                    </div>
                </div>

                <div class="panel-body table-responsive" >
                    <!--Listing des paramètres utilisateur -->
                    <table class="table table-striped  bootstrap-datatable small-font dataTable " >
                        <thead>
                          <tr>
                            <th>Id</th>
                            <th>User ref</th>
                            <th>User</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Value</th>
                            <th>Created at</th>
                            <th>Updated at</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($ei_user_params as $ei_user_param): ?>
                          <?php include_partial('eiuserparam/user_param_line',array('ei_user_param'=>$ei_user_param)) ?>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    <!--Fin listing des paramètres utilisateur-->
                </div>
                <div class="panel-footer">
                    <!--Ajout d'un paramètre utilisateur-->
                    <a href="<?php echo url_for('eiuserparam/new') ?>" id="addUserParam">
                        New
                    </a> 
                </div> 
        </div> 
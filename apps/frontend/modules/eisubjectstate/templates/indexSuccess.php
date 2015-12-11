<?php $url_tab = array(
            'project_id' => $project_id,
            'project_ref' => $project_ref,
            'profile_name' => $profile_name,
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref );  
?>    
<div class="panel panel-default eiPanel">
            <div class="panel-heading">
                <h2><i class="fa fa-genderless"></i> Bugs state </h2>
                <div class="panel-actions"> 
                </div>
            </div>

            <div class="panel-body table-responsive" >
                <table class="table small-font   table-condensed table-striped dataTable" id="eiBugsStatesList">
                    <thead>
                    <tr>
                        <th>  Name </th>
                        <th>    Color </th>
                        <th>   Display homepage? </th>
                        <th>   Display Search? </th>
                        <th> Display to do list?</th>
                        <th> close delivery state?</th>
                        <th>Updated At</th> 
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (count($ei_subject_states)>0): ?>
                        <?php foreach ($ei_subject_states as $state): ?>
                        <?php $stateLineParams=$url_tab; $stateLineParams['state']=$state; ?>
                        <?php include_partial('stateLine',array('stateLineParams'=>$stateLineParams)) ?>
                            
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div> 
        </div> 
 
<div  id="bugsStateBox"  class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
            <i class="fa fa-genderless"></i> Edit state
        </h4>
      </div>
      <div class="modal-body" id="bugsStateBoxContent"> 
      </div>
<!--      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button> 
      </div>-->
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
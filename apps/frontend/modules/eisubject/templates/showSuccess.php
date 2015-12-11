<?php if ($ei_subject != null): ?>
    <?php
    $url_tab = array(
        'project_id' => $project_id,
        'project_ref' => $project_ref,
        'profile_name' => $profile_name,
        'profile_id' => $profile_id,
        'profile_ref' => $profile_ref);
    $subject_edit = $url_tab;
    $subject_edit['subject_id'] = $ei_subject_with_relation['id'];
//Récupération d'une éventuelle livraison associée au bug
    if (isset($ei_subject_with_relation['EiDelivery'])):
        $ei_delivery = $ei_subject_with_relation['EiDelivery'];
        //Vérification de l'existance du statut de la livraison du bug
        if (isset($ei_delivery['EiDeliveryState'])): $del_state = $ei_delivery['EiDeliveryState'];
        endif;
    endif;
//Récupération du statut du bug 
    if (isset($ei_subject_with_relation['EiSubjectState'])):
        $state = $ei_subject_with_relation['EiSubjectState'];
    endif;
    ?>
    <?php include_partial('global/alertBox', array('flash_string' => 'alert_form')) ?> 
    <div id="subjectContent" class="row">



        <div class="panel panel-default eiPanel" >
            <div class="panel-heading">
                <h2><strong><i class="fa fa-wrench"></i>Properties </strong> /  <?php echo ei_icon('ei_show') ?>  </h2> 

                <div class="panel-actions"> 

                        <?php if (!isset($del_state) || !$del_state['ds_close_state']): ?>
                        <a id="editBug" class=" btn-default " 
                           href="<?php echo url_for2('subject_edit', $subject_edit) ?>"> 
                        <?php echo ei_icon('ei_edit') ?> Edit 
                        </a> 

    <?php endif;?>
                </div>
                <?php if(!(isset($defaultIntervention)&& isset($defaultIntervention['package_id']) && $defaultIntervention['package_id']==$ei_subject_with_relation['package_id'] && $defaultIntervention['package_ref']==$ei_subject_with_relation['package_ref'])): ?>
                <ul class="nav nav-tabs">
                    <li>
                    <?php $setIntAsCurrentUri = $url_tab;
                    $setIntAsCurrentUri['subject_id'] = $ei_subject_with_relation['id'];
                    $setIntAsCurrentUri['action'] = "setInterventionAsDefault"; ?>
                        <a href="#" id="setInterventionAsDefault" itemref="<?php echo url_for2("intervention_actions", $setIntAsCurrentUri) ?>">
                            <i class="fa fa-spinner fa-spin eiLoading" style="display:none"></i> Load
                        </a>
                    </li>
                </ul>
                <?php endif; ?>
            </div>
            <div class="panel-body">
                <div class="panel-body"> 
                    <div class="panel panel-default eiPanel">
                        <div class="panel-heading"> 
                            <h2><strong><i class="fa fa-info"></i>Main informations </strong>   </h2> 
                        </div> 
                        <div class="panel panel-body">
                            <div  class="row" id="subjectContentProperties">
                                <div class="col-lg-6 col-md-6">
                                    <table class="table table-bordered table-striped dataTable"> 
                                        <tbody> 
                                            <tr>
                                                <th>Title</th>
                                                <td><?php echo $ei_subject_with_relation['name'] ?></td>
                                            </tr>
                                            <tr>
                                                <th>Author</th>
                                                <td><?php echo $ei_subject_with_relation['sfGuardUser']['author_username'] ?></td>
                                            </tr>
                                            <tr>
                                                <th>State</th>
                                                <td>

                                                    <?php if (isset($state)): ?>
                                                        <span style="background-color:<?php echo $state['st_color_code'] ?> " class="label ">
        <?php echo $state['st_name']; ?>
                                                        </span> 
    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Priority</th>
                                                <td><?php echo $ei_subject_with_relation['EiSubjectPriority']['p_name'] ?></td>
                                            </tr>
                                            <tr>
                                                <th>Type</th>
                                                <td><?php echo $ei_subject_with_relation['EiSubjectType']['t_name'] ?></td>
                                            </tr>
                                            <tr>
                                                <th>Coverage</th>
                                                <td> 
                                                    <?php
                                                    if (isset($ei_subject_with_relation['subjectCampaigns']) && count($ei_subject_with_relation['subjectCampaigns']) > 0):
                                                        $tnr = $ei_subject_with_relation['subjectCampaigns'][0];
                                                        if (isset($tnr['EiCampaign']) && $tnr['EiCampaign']['tnr_coverage'] != null):
                                                            echo $tnr['EiCampaign']['tnr_coverage'] . ' %';
                                                        else : echo '0 %';
                                                        endif;
                                                    else:
                                                        echo '0 %';
                                                    endif;
                                                    ?>  
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Delivery</th>
                                                <td> 
    <?php if (isset($ei_delivery) && !empty($ei_delivery)): ?>  
                                                               <?php $getDeliverySubjects = $url_tab;
                                                               $getDeliverySubjects['delivery_id'] = $ei_delivery['d_delivery_id']
                                                               ?>
                                                        <a class="tooltipUser"  data-toggle="tooltip" 
                                                           href="<?php echo url_for2('getDeliverySubjects', $getDeliverySubjects) ?>" 
                                                           data-original-title="<?php echo $ei_delivery['delivery_name'] ?>">
        <?php echo ei_icon('ei_delivery') ?>
        <?php echo MyFunction::troncatedText($ei_delivery['delivery_name'], 25) ?>  
                                                        </a>
    <?php endif; ?> 
                                                </td>
                                            </tr> 
                                            <tr>
                                                <th>External ID</th>
                                                <td><?php echo $ei_subject_with_relation['alternative_system_id'] ?></td>
                                            </tr>
                                        </tbody>
                                    </table> 
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <table class="table table-bordered table-striped"> 
                                        <tbody> 
                                            <tr>
                                                <th>Creation </th>
                                                <td>
                                                    <?php
                                                    $now = time();
                                                    $creation_date = $ei_subject['created_at'];
                                                    $diff = $now - strtotime($creation_date);
                                                    $nb_days = floor($diff / (60 * 60 * 24));
                                                    if(($nb_days != 0) && ($nb_days != 1))
                                                    {
                                                        echo $nb_days . ' days ago';
                                                    }
                                                    else
                                                    {
                                                        if($nb_days == 0)
                                                        {
                                                            $nb_hours = floor($diff / (60 * 60));
                                                            if($nb_hours != 0 && $nb_hours != 1)
                                                            {
                                                                echo $nb_hours . ' hours ago';
                                                            }
                                                            else
                                                            {
                                                                echo $nb_hours . ' hour ago';
                                                            }
                                                        }
                                                        else
                                                        {
                                                            echo $nb_days . ' day ago';
                                                        }
                                                    }
                                                    ?> 
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Updated at</th>
                                                <td><?php echo $ei_subject_with_relation['updated_at'] ?>  
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Development time</th>
                                                <td><?php echo $ei_subject_with_relation['development_time'] ?>  
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Development estimation</th>
                                                <td><?php echo $ei_subject_with_relation['development_estimation'] ?>  
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Test time</th>
                                                <td><?php echo $ei_subject_with_relation['test_time'] ?>  
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Test estimation</th>
                                                <td><?php echo $ei_subject_with_relation['test_estimation'] ?>  
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Expected date</th>
                                                <td><?php echo $ei_subject_with_relation['expected_date'] ?>  
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table> 
                                </div> 
                            </div> 

                        </div> 
                    </div> 
                    <div class="panel panel-default  eiPanel" id="subjectContentDescription">
                        <div class="panel-heading">
                            <h2><strong><i class="fa fa-text-width "></i>Resume</strong></h2> 
                        </div>
                        <div class="panel-body" >  

                            <?php
                            // The Regular Expression filter
                            //$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
                            // The Text you want to filter for urls
                            $text = $ei_subject_with_relation['description'];

                            echo str_replace("\n", '<br/>', str_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;", preg_replace('!((https?|ftp)://[^\s]+)!i', '<a href="$1" target="_blank">$1</a> ', $text . " ")));
                            //$desc = str_replace("\n",'<br/>',str_replace("\t","&nbsp;&nbsp;&nbsp;&nbsp;",$text));
                            ?>

                        </div> 
                    </div> 
                </div>     




            </div>
        </div>



        <div class="panel panel-default eiPanel" id="subjectAssignUsers">
            <div class="panel-heading">
                <h2>
                    <i class="fa fa-user "></i>
                    <span class="break"></span>  Assignments</a>
                </h2>
                <div class="panel-actions"> 
                    <a href="#bugAssignmentsTab" class="btn-default " role="tab" data-toggle="tab" title="Intervention assignments">
    <?php echo ei_icon('ei_user') ?>  
                    </a> 
                    <a href="#bugAssignmentsHistoryTab" class="btn-default  " role="tab" data-toggle="tab" title="Assignments history">
                        <i class="fa fa-history"></i> 
                    </a> 
                </div>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="bugAssignmentsTab">
                        <?php
                        $assignUsers = $url_tab;
                        $assignUsers['ei_subject'] = $ei_subject;
                        $assignUsers['alreadyAssignUsers'] = $alreadyAssignUsers;
                        $assignUsers['projectUsers'] = $projectUsers;
                        include_partial('eisubject/assignUsers', $assignUsers);
                        ?> 
                    </div>
                    <div role="tabpanel" class="tab-pane" id="bugAssignmentsHistoryTab">
                        <?php
                        $bugAssignmentHistory = $url_tab;
                        $bugAssignmentHistory['ei_subject'] = $ei_subject;
                        $bugAssignmentHistory['bugAssignmentHistorys'] = $bugAssignmentHistorys;
                        ?>
                        <?php include_partial('eisubject/bugAssignmentHistory', $bugAssignmentHistory) ?>
                    </div> 
                </div> 
            </div>
        </div>
        <div class="panel panel-default eiPanel" id="subjectAttachments">
            <div class="panel-heading" id="subjectAttachmentsTitle">
                <h2>
                    <i class="fa fa-upload"></i>
                    <span class="break"></span>  Attachments</a>
                </h2>
                <div class="panel-actions"> 
                    <a href="#uploadAttachment" role="button" class="btn-default" data-toggle="modal" title="Add attachement">
                <?php echo ei_icon('ei_add') ?>
                    </a> 
                </div>
            </div>
            <div class="panel-body"> 
    <?php
    $eisubjectattachment_list = $url_tab;
    $eisubjectattachment_list['subjectAttachments'] = $subjectAttachments;
    $eisubjectattachment_list['form'] = $newAttachForm;
    $eisubjectattachment_list['subject_id'] = $subject_id;
    include_partial('eisubjectattachment/list', $eisubjectattachment_list)
    ?>
            </div>
        </div>


        <?php
//            include_partial('eisubjectmessage/list',array(
//                'subject_id'=> $subject_id,
//                'project_id'=> $project_id,
//                'project_ref'=>$project_ref,
//                'subjectMessages' => $subjectMessages,
//                'type' => sfConfig::get('app_bug_description_message')
//            )) 
        ?>
        <!--            
                    <div class="row panel panel-default eiPanel" id="subjectDescriptionMessages">
                        <div class="panel-heading" id="">
                            <h2>
                                 <i class="fa fa-comments-o"></i>
                                <span class="break"></span>  Discussions  </a>
                            </h2>
                            <div class="panel-actions">  
                            </div>
                        </div>
                        <div class="panel-body itemMessages">
                            <div class="itemMessage" contenteditable="true">  </div>	
        <?php //if(isset($subjectMessages) && count($subjectMessages->getRawValue())> 0):   ?>	
        <?php //foreach($subjectMessages as $rootMessage): ?>
        <?php
        //$eisubjectmessage_item=$url_tab;
        //$eisubjectmessage_item['ei_subject_message']= $rootMessage;  
        //include_partial('eisubjectmessage/item',$eisubjectmessage_item ) 
        ?>
    <?php //endforeach;  ?>
    <?php //endif;  ?>
                        </div>
                        <div class="panel-footer">
        <?php
//                    $ei_msg_subject_new=$subject_edit; 
//                    $ei_msg_subject_new['message_type_id']=$msgQuestion->getId();
//                    $ei_msg_subject_new['type']=sfConfig::get('app_bug_description_message'); 
//                    $ei_msg_subject_new['parent_id']=0;
//                    include_partial('eisubjectmessage/quickForm',$ei_msg_subject_new) 
        ?> 
                        </div>
                        </div>-->

<?php endif; ?>









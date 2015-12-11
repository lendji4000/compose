 
<?php if (isset($guard_user) && $guard_user != null): ?> 
    <?php echo 'Hello   ' . $guard_user->getUserName() . '  .You have an new intervention assigned to you. Please check it'; ?>  
<?php endif; ?> 
<div class="panel panel-default eiPanel" id="assignNotification" style="
     margin-top: 10px;
     border: none;
     border: 1px solid #4a4a4a;
     -moz-box-shadow: 1px 1px 1px 0px #4a4a4a;
     -webkit-box-shadow: 1px 1px 1px 0px #4a4a4a;
     -o-box-shadow: 1px 1px 1px 0px #4a4a4a;
     box-shadow: 1px 1px 1px 0px #4a4a4a;">
    <div class="panel-heading" style="
         padding: 0 1px;
         padding-left: 3px;
         border-bottom: 1px solid #d4d4d4;
         background-color: #177b8c;
         /*border:none;*/
         border-color: #4a4a4a;
         -moz-box-shadow: 1px 1px 1px 0px #4a4a4a;
         -webkit-box-shadow: 1px 1px 1px 0px #4a4a4a;
         -o-box-shadow: 1px 1px 1px 0px #4a4a4a;
         box-shadow: 1px 1px 1px 0px #4a4a4a;
         color: #eeeeee;
         ">
        <h2 style="color: #eeeeee;"><a style="color: #eeeeee; text-decoration: none">
                <i class="fa fa-text-width " style="color: #eeeeee;"></i>
                <span class="break"></span>
                    <?php echo $connectedGuard->getUsername() ?>
                <small> assign an intervention to you</small> 
            </a>
        </h2> 
    </div>
    <div class="panel-body"  >   
        <table>
            <tbody >
                <tr>
                    <th style="text-align: left">Id</th>
                    <td style="text-align: right"><?php echo 'S' . $ei_subject->getId() ?></td>
                </tr>
                <tr>
                    <th style="text-align: left">Name</th>
                    <td style="text-align: right"><?php echo $ei_subject ?></td>
                </tr>
                <tr>
                    <th style="text-align: left">Description</th>
                    <td style="text-align: right"><?php echo $ei_subject->getDescription() ?></td>
                </tr>
                <tr> 
                    <th style="text-align: left">Assign date</th>
                    <td style="text-align: right"> <?php echo date('Y-m-d H:i:s'); ?></td>
                </tr>
                <tr>
                    <th style="text-align: left">Follow the bug</th>
                    <td style="text-align: right">
                        <?php
                        $subject_edit = $urlParameters->getRawValue();
                        $subject_edit['subject_id'] = $ei_subject->getId();
                        echo $sf_request->getUriPrefix() . url_for2('subject_show', $subject_edit);
                        ?>
                    </td>
                </tr>   
            </tbody>
        </table>
    </div>
</div>
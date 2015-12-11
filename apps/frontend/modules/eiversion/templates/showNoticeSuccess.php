<?php
$url_tab=array(
         'project_id' => $project_id,
         'project_ref' =>$project_ref,
         'profile_id' => $profile_id,
         'profile_ref' => $profile_ref,
         'profile_name' => $profile_name
     )?>   
<?php use_helper('Date') ?>
<?php use_stylesheet('notice.css') ?>

<table  cellspacing="5px" cellpadding="5px" class="table table-bordered table-striped" >
    <tr>
        <td width="35%"> 
            <img src="/images/logos/logo-kalifast-scenario.png" alt="" /> 
            <h4><?php echo date('d/m/Y') ?></h4> 
        </td>
        <td>
            <h3 class="entete_notice">Test Suite  :  <?php echo $ei_scenario ?></h3>
        </td>
        <td width="30%">

            <h4>Project : <?php echo $ei_project ?></h4> 
            <h4>Environment : <?php echo $ei_profile ?></h4> 
            <h4>Version : <?php echo $ei_version->getLibelle() ?></h4> 
            <h4>User : <?php echo $user ?></h4> 
        </td>

    </tr>
</table> 
<?php $profileParams = $ei_profile->getParams(); ?>
<table class=" table table-bordered table-striped list_notice_table"  >
    <?php if (isset($list_notices) && count($list_notices) > 0): ?> 
        <?php foreach ($list_notices as $name => $fonctions): ?>
            <tr>
                <th colspan="2"><h2 class="img_function_name"><?php echo $name; ?></h2></th>
        </tr> 
        <?php foreach ($fonctions as $id_fonction => $noticeVersion): ?> 
            <?php if (sfOutputEscaper::unescape($noticeVersion) != null): ?>
                <tr> 
                    <td class="desc_img_notice">
                        <!-- Traitement des paramètres variables dans la notice -->
                        <?php 
                        echo html_entity_decode(MyFunction::parseDescImg(
                                        $noticeVersion['description'], $id_fonction, $profileParams));
                        ?>
                    </td> 
                </tr> 
                <?php else: ?>
                <tr> 
                    <td class="desc_img_notice">
                        <h6>No Notice Version define for this environment and language</h6>
                    </td> 
                </tr> 
            <?php endif; ?> 
        <?php endforeach; ?>    
    <?php endforeach; ?>             
<?php else: ?>
    <tr>
        <th colspan="2">Aucune Notice retrouvée !</h2></th>
    </tr>
<?php endif; ?>
</table>
<?php
    $nbSuccess = 0;
    $nbFailed = 0;
    $nbAborted = 0;
    $nbProcessing = 0;
    $nbEtapes = 0;

    foreach( $oracle as $notice ){
        $statut = strtolower($notice["statut_func_name"]);

        if( $statut == StatusConst::STATUS_OK ){
            $nbSuccess+=1;
        }

        if( $statut == StatusConst::STATUS_KO ){
            $nbFailed+=1;
        }

        if( $statut == StatusConst::STATUS_NA ){
            $nbAborted+=1;
        }

        $nbEtapes++;
    }
?>
<h3>
    Oracle of <b><q><?php echo $ei_scenario->getNomScenario(); ?></q></b>
    &nbsp;-&nbsp;
    Execution N°<?php echo $ei_test_set->getId(); ?> at <?php echo $ei_test_set->getCreatedAt() ?>
    &nbsp;
    <span style="background-color:<?php echo $ei_test_set->getStatusColor() ?> " class="label">
        <?php echo $ei_test_set->getStatusName(); ?>
    </span>
</h3>

<table class="table table-bordered table-condensed">
    <thead>
    <tr>
        <th width="10%">Device/Browser</th>
        <th width="18%">Step Count</th>
        <th width="18%">Executed Step Count</th>
        <th width="18%">Success</th>
        <th width="18%">Failed</th>
        <th width="18%">Aborted</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <?php $ei_test_set->displayDeviceBrowserData(); ?>
        </td>
        <td><?php echo $nbEtapes; ?></td>
        <td>
            <?php echo $ei_test_set->getNbFctExecutees() . ' (' . number_format(($ei_test_set->getNbFctExecutees()/$ei_test_set->getNbFct()) * 100, 2) . '%)'; ?>
        </td>
        <td>
            <div data-type="state-success">
                <?php echo $nbSuccess ?> (<?php echo ($nbEtapes > 0) ? number_format($nbSuccess*100/$nbEtapes, 2):0 ?>%)
            </div>
        </td>
        <td>
            <div data-type="state-failed">
                <?php echo $nbFailed ?> (<?php echo ($nbEtapes > 0) ? number_format($nbFailed*100/$nbEtapes, 2):0 ?>%)
            </div>
        </td>
        <td>
            <div data-type="state-aborted">
                <?php echo $nbAborted ?> (<?php echo ($nbEtapes > 0) ? number_format($nbAborted*100/$nbEtapes, 2):0 ?>%)
            </div>
        </td>
    </tr>
    </tbody>
</table>

<br />

<table class="table table-bordered table-condensed"> 
    <thead>
        <tr>
            <td>N°</td>
            <td>Function</td>
            <td>Description</td>
            <td>Expected</td>
            <td>Result</td>
            <td>Selenium Logs</td>
            <td>Selenium Status</td>
        </tr> 
    </thead>
    <tbody>
        <?php foreach($oracle as $key => $notice): ?>
        <?php //Recherche des paramètres de la fonction pour interpréter celles variables dans le jeu de test
        $params=Doctrine_Core::getTable('EiTestSetParam')
                ->getParamForTestSetAndEiTestFunction($ei_test_set_id,$notice['ei_test_set_function_id']);

        $paramsOut=Doctrine_Core::getTable('EiTestSetParam')
                ->getParamForLogAndEiTestSetFunction($ei_test_set_id,$notice['ei_test_set_function_id']);

        $urlFuncSubjects = url_for2("subjectFunction", array(
            "function_id" => $notice['function_id'],
            "function_ref" => $notice['function_ref'],
            "action" => "functionSubjects",
            'profile_name' => EiProfil::slugifyProfileName($profile_name),
            'profile_id' => $profile_id,
            'profile_ref' => $profile_ref,
            'project_id' => $project_id,
            'project_ref' => $project_ref,
        ));
        ?>
        <tr class="state-<?php echo strtolower($notice["statut_func_name"]); ?>">
            <td><?php echo $notice['position'];//.'/'.count($params).'/'.count($profileParams) ?></td>
            <td>
                <a href="<?php echo $urlFuncSubjects; ?>" target="_blank">
                    <?php echo $notice["function_name"] ?>
                </a>
                <br />

                <strong><?php echo $notice["xpath"] ?></strong>

                <br />

                <?php echo $notice["func_desc"] ?>

                <br /><br />

                <?php if(count($params) > 0): ?>
                    <strong>In parameters:</strong>
                    <br />
                    <ul>
                        <?php foreach( $params as $pIn ): ?>
                            <li><strong><?php echo $pIn["name"]; ?></strong>:&nbsp;<?php echo htmlentities($pIn["valeur"]) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <br />

                <?php if(count($paramsOut) > 0): ?>
                    <strong>Out parameters:</strong>
                    <br />
                    <ul>
                        <?php foreach( $paramsOut as $pOut ): ?>
                            <li><strong><?php echo $pOut["param_name"]; ?></strong>:&nbsp;<?php echo htmlentities($pOut["param_valeur"]) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </td>
            <td> 
                <?php $ch1= MyFunction::parseAndExtractParamsValue($notice['final_desc'],$params,$profileParams) ?>
                <?php echo MyFunction::parseAndExtractOutParamsValue($ch1,$paramsOut) ?>
            </td>
            <td>
                <?php $ch1= MyFunction::parseAndExtractParamsValue($notice['final_expected'],$params,$profileParams) ?>
                <?php echo MyFunction::parseAndExtractOutParamsValue($ch1,$paramsOut)?>
            </td>
            <td>
                <?php $ch1= MyFunction::parseAndExtractParamsValue($notice['final_result'],$params,$profileParams) ?>
                <?php echo MyFunction::parseAndExtractOutParamsValue($ch1,$paramsOut) ?>
            </td>
            <td>
                <?php $selLogs = explode("///", $notice["sel_logs"]); ?>
                <ul>
                    <?php foreach($selLogs as $selLog): ?>
                        <?php $haveError = preg_match("/\[error\]/", $selLog) ?>
                        <?php $haveComment = preg_match("/\[comment\]/", $selLog) ?>
                        <li <?php if($haveError): ?>style="color: red;"<?php elseif($haveComment): ?>style="color: green; font-weight: bolder"<?php endif; ?>><?php echo htmlentities($selLog); ?></li>
                    <?php endforeach; ?>
                </ul>
            </td>
            <td>
                <span style="background-color:<?php echo $notice["statut_func_color"] ?> " class="label">
                  <?php echo $notice["statut_func_name"]; ?>
                </span>
            </td>
        </tr>
        <?php endforeach; ?> 
    </tbody> 
</table>

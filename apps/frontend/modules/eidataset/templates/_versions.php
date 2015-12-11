<?php
$count = $versions->count();

$urlExcelRequest = url_for2("api_generate_excel_request_api", array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name
));

// Génération des paramètres pour l'url permettant de changer de version.
$urlChangeParams = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'profile_name' => $profile_name
);
?>
<div class="panel panel-default eiPanel">
    <div class="panel-heading" data-original-title>
        <h2 class="title_project">
            <?php echo ei_icon('ei_version') ?>
            <span class="break"></span>
            Versions (<?php echo $count ?>)
        </h2>
        <div class="panel-actions"> 
        </div>
    </div>
    <div class="panel-body table-responsive">
        <table class="table table-striped small-font bootstrap-datatable dataTable " id="EiPaginateList">
            <thead>
            <tr>
                <th>Current</th>
                <th>Name</th>
                <th>Created at</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php /** @var EiDataSet $version */ ?>
            <?php foreach ($versions as $i => $version): ?>
                <?php
                    // On modifie les paramètres en incluant l'id du jeu de données.
                    $urlChangeParams["ei_data_set_template_id"] = $version->getEiDataSetTemplateId();
                    // Puis, on génère l'URL permettant de modifier la version courante.
                    $urlChange = url_for2("eidataset_template_change_version", $urlChangeParams);

                    // On vérifie le statut de la version : courante ou non.
                    $checked = $version->getEiDataSetTemplate()->getEiDataSetRefId() == $version->getId() ? 'checked="checked"':'';
                ?>
                <tr>
                    <td>
                        <input type="radio" name="jdd_version_courante" data-href="<?php echo $urlChange ?>" value="<?php echo $version->getId() ?>" <?php echo $checked ?> />
                    </td>
                    <td>
                        <?php echo ei_icon('ei_version') ?>
                        <?php echo $version->getName(); ?>
                    </td>
                    <td><?php echo date('Y-m-d H:i:s', strtotime($version->getCreatedAt())); ?></td>
                    <td>
                        <a href="<?php echo $urlExcelRequest;?>" class="excel-open-jdd excelIcon noUpdate" title="Open in Excel" data-preserve="original" data-id="<?php echo $version->getId() ?>">
                            <img src="<?php echo sfConfig::get("app_icone_excel_24x24_path"); ?>" alt="" width="20" title="Open data set in Excel" class="excel-icon-img disabledOracle" />
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
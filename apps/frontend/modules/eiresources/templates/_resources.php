<?php

$host = 'http://' . str_replace('frontend_dev.php/', '', sfConfig::get("project_prefix_path"));

// Génération des liens Excel.
$server = sfConfig::get("app_excel_plateforme");
$fDir = sfConfig::get("app_excel_download_dir");
$latest = sfConfig::get("app_excel_download_latest");

$excelWindows = $server.$fDir.$latest;

// Génération des liens IDE.
$server = sfConfig::get("app_plugin_ide_plateforme");
$fDir = sfConfig::get("app_plugin_ide_download_dir");
$latest = sfConfig::get("app_plugin_ide_download_latest");

$ideWindows = $server.$fDir.$latest;

// Génération des liens IDE.
$server = sfConfig::get("app_kalifast_service_plateforme");
$fDir = sfConfig::get("app_kalifast_service_download_dir");
$latest = sfConfig::get("app_kalifast_service_download_latest");

$kalifastServiceWindows = $server.$fDir.$latest;

// Liens externes vers les autres ressources
$firefox = sfConfig::get("app_firefox_resources");
$selIde = sfConfig::get("app_selenium_ide_ressources");
$selBlocks = sfConfig::get("app_selblocks_ressources");

?>
<div class="col-lg-12">
    <div class="panel panel-default eiPanel">
        <div class="panel-heading">
            <h2><?php echo ei_icon('ei_download') ?>DOWNLOAD RESOURCES</h2>
        </div>
        <div class="panel-body">
            <table class="table table-striped vAlignMiddle">
                <tr>
                    <th class="vAlignMiddle">
                        <img src="<?php echo sfConfig::get("app_icone_firefox_24x24_path"); ?>" alt="SELENIUM IDE" width="24" height="24" />&nbsp;&nbsp;Firefox
                    </th>
                    <td>
                        <a href="<?php echo $firefox; ?>" id="downloadFirefox" target="_blank" title="Download Firefox browser">Download</a>
                    </td>
                </tr>
                <tr>
                    <th class="vAlignMiddle">
                        <img src="<?php echo sfConfig::get("app_logo_kalifast"); ?>" alt="SELENIUM IDE" width="24" height="24" />&nbsp;&nbsp;Kalifast service
                    </th>
                    <td>
                        <a href="<?php echo $kalifastServiceWindows; ?>" id="downloadKalifastService" target="_blank" title="Download Kalifast service">Download</a>
                    </td>
                </tr>
                <tr>
                    <th class="vAlignMiddle">
                        <img src="<?php echo sfConfig::get("app_icone_selide_24x24_path"); ?>" alt="SELENIUM IDE" width="24" height="24" />&nbsp;&nbsp;Selenium IDE
                    </th>
                    <td>
                        <a href="<?php echo $selIde; ?>" id="downloadSelIDE" target="_blank" title="Download Selenium IDE">Download</a>
                    </td>
                </tr>
                <tr>
                    <th class="vAlignMiddle">
                        <img src="<?php echo sfConfig::get("app_icone_selide_24x24_path"); ?>" alt="SELENIUM IDE" width="24" height="24" />&nbsp;&nbsp;Plug-in Selblocks for Selenium IDE
                    </th>
                    <td>
                        <a href="<?php echo $selBlocks; ?>" id="downloadSelBlocks" target="_blank" title="Download Selenium plug-in Selblocks">Download</a>
                    </td>
                </tr>
                <tr>
                    <th class="vAlignMiddle">
                        <img src="<?php echo sfConfig::get("app_icone_excel_24x24_path"); ?>" alt="EXCEL" width="24" height="24" />&nbsp;&nbsp;Plug-in Kalifast for Excel
                    </th>
                    <td>
                        <a href="<?php echo $excelWindows; ?>" id="downloadExcelWin32" target="_blank" title="Download Excel plug-in Kalifast for windows 32-bit">Download for Windows 32-bit</a>
                        &nbsp;-&nbsp;
                        <a href="<?php echo $excelWindows; ?>" id="downloadExcelWin64" target="_blank" title="Download Excel plug-in Kalifast for windows 64-bit">Download for Windows 64-bit</a>
                    </td>
                </tr>
                <tr>
                    <th class="vAlignMiddle">
                        <img src="<?php echo sfConfig::get("app_icone_selide_24x24_path"); ?>" alt="SELENIUM IDE" width="24" height="24" />&nbsp;&nbsp;Plug-in Kalifast for Selenium IDE
                    </th>
                    <td>
                        <a href="<?php echo $ideWindows; ?>" id="downloadFirefoxIDE" target="_blank" title="Download Selenium plug-in Kalifast">Download</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
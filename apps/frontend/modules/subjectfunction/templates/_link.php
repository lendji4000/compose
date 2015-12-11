<?php 
$url_tab = array(
    'project_id' => $project_id,
    'project_ref' => $project_ref,
    'profile_name' => $profile_name,
    'profile_id' => $profile_id,
    'profile_ref' => $profile_ref,
    'function_id' => $function_id,
    'function_ref' => $function_ref
);
if (isset($exist_link) && $exist_link):
    if (isset($automate) && $automate):
        $class = 'ei-strong-link';
        $href = "#";
        $title="Function is strongly link with current package...";
    else:
        $class = 'ei-breakable-link';
        $href = url_for2("linkFunctionWithDefPack", $url_tab);
        $title="Delete link between function and current package ? ";
    endif;
else:
    $class = 'ei-notfound-link';
    $href = url_for2("linkFunctionWithDefPack", $url_tab);
    $title="Link function and current package ? ";
endif;
?>
<a itemref="<?php echo $href ?>" class="<?php echo 'btn-link '.$class?>" title="<?php echo $title ?>"> 
        <?php echo ei_icon('ei_subject') ?>
</a>
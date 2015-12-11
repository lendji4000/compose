<?php        
if(!isset($position))  $position = 0; 
include_partial('eifonction/formContent', array(
                        'is_editable'=> true, // Notifie le caractère éditable ou non de la fonction et permet notamment d'afficher le lien de suppression et autres actions reservées
                        'eiFonction'  => $form,
                        'paramsForUrl' => $paramsForUrl,
                        'obj' => (isset($obj)?$obj:null)
                    ));

include_partial('eiversion/curseurRow',
        array('paramsUrl' => $paramsForUrl,
              'insert_after' => $insert_after)); 
?>

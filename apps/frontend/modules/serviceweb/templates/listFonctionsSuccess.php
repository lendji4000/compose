<?php

if (isset($objects)): 
    foreach ($objects as $object): 
            if (sfOutputEscaper::unescape($object) instanceof EiFonction):
                    echo sfOutputEscaper::unescape($object); 
                else:
                    $object->printVersion();
                endif;
    endforeach;

endif;
?>

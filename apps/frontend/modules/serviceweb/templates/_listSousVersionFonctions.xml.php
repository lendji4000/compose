<?php if(isset($objects) ) : ?>
<?php

foreach ($objects as $object):
            if (sfOutputEscaper::unescape($object) instanceof EiFonction): 
                $fonction= sfOutputEscaper::unescape($object); ?>
                <test>
                    <url>
                        <?php echo str_replace('&', '&amp;',$prefix.url_for('@generateFunctionXMLLink?profile_id='.$profile_id.
                                '&profile_ref='.$profile_ref.'&function_id='.$fonction->getId().'&login='.$login.'&pwd='.$pwd.'&sf_format=xml'))  ?>
                    </url>
                    <nom><?php echo $fonction ?></nom>
                </test>
    <?php        else:
            $objects=$sousVersion->getOrderedContent();
            if(count($objects) > 0):
                include_partial('serviceweb/listSousVersionFonctions',array($objects)); 
            endif;
            endif;
        endforeach;
?>
<?php endif;?>
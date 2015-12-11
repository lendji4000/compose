
<tests>
    <?php $prefix='http://'.$sf_request->getHost() ?>
    <?php if(isset($objects) ) : ?>
    <?php
    
        foreach ($objects as $object):
            if (sfOutputEscaper::unescape($object) instanceof EiFonction): 
                $fonction= sfOutputEscaper::unescape($object);
                $kal_fonction=$fonction->getKalFonction();?>
                <test>
                    <url>
                        <?php echo str_replace('&', '&amp;',$prefix.url_for('@generateFunctionXMLLink?profile_id='.$profile_id.
                                '&profile_ref='.$profile_ref.'&function_id='.$fonction->getId().'&login='.$login.'&pwd='.$pwd.'&sf_format=xml'))  ?>
                    </url>
                    <url_xml>
                        <?php echo $prefix.$sf_request->getPathInfoPrefix()."/eifonction/generateXML?function_id=".$fonction->getId().
                                "&amp;profile_id=".$profile_id."&amp;profile_ref=".$profile_ref.'&amp;login='.$login.'&amp;pwd='.$pwd.'&amp;sf_format=xml' ?>
                        
                    </url_xml>
                    <url_xsl>
                        <?php echo $prefix.$sf_request->getPathInfoPrefix()."/eifonction/genererXSL/".$login.'/'.$pwd.'/'.
                                $kal_fonction->function_id."/".$kal_fonction->function_ref."/".$profile_id."/".$profile_ref.".xml" ?>
                    </url_xsl>
                    <nom><?php echo $fonction ?></nom>
                </test>
    <?php        else:
            $objects=$object->getOrderedContent();
            if(count($objects) > 0):
                include_partial('serviceweb/listSousVersionFonctions',
                        array('objects'=> $objects,'profile_id' => $profile_id, 'profile_ref' => $profile_ref,
                                'login' => $login ,'pwd' => $pwd , 'prefix' => $prefix)); 
            endif;
            endif;
        endforeach;
?>  
<?php else :  ?>
    <no_function></no_function>
<?php endif; ?>
</tests>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sfWidgetFormTextareaTinyMCECustom 
 *
 * @author eisge
 */
class sfWidgetFormTextareaTinyMCECustom  extends sfWidgetFormTextarea {
    
    
     public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    //$textarea = parent::render($name, $value, $attributes, $errors); 
    //La ligne ci-dessous est utilisée contrairement à celle au dessus pour 
    //permettre l'utilisation du code tel que saisit par l'utilisateur ( htmlentities($value) )      
    $textarea = parent::renderContentTag('textarea', htmlentities($value), array_merge(array('name' => $name), $attributes));
    $js = sprintf(<<<EOF
    <script type="text/javascript">
    $(document).ready(function() {
        tinymce.init({
                selector: "textarea.tinyMceContent , textarea.tinyMceNoticeExpect ,textarea.tinyMceNoticeResult", 
                paste_data_images: true , 
                plugins: [
                    " example advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table  paste "
                ],   
                menu : { 
                    //edit: { title: 'Edit', items: 'undo redo  | cut copy paste selectall | searchreplace' },
                    insert: { title: 'Insert', items: 'link charmap' },
                    format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript | removeformat' },
                    table: { title: 'Table', items: 'inserttable tableprops deletetable | cell row column' }
                  },
                //menubar: "file edit format table view insert tools parameters",
                entity_encoding: "raw",
                toolbar: " undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent "
                
            });
    });
       

    </script>
EOF
    ,
      $this->generateId($name),
      $this->getOption('width')  ? sprintf('width:                             "%spx",', $this->getOption('width')) : '',
      $this->getOption('height') ? sprintf('height:                            "%spx",', $this->getOption('height')) : '',
      $this->getOption('config') ? ",\n".$this->getOption('config') : ''
    );
    
    return $textarea.$js;
    
}


 protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('theme', 'advanced');
    $this->addOption('width');
    $this->addOption('height');
    $this->addOption('config', '');
  }
}

//$('textarea.tinymce').tinymce({
//        // Location of TinyMCE script  
//                        
//        // General options
//        theme : "advanced",
//        plugins : "eisgePlugin, pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
//        
//        // Theme options
//        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
//        theme_advanced_buttons2 : "bullist,numlist,|,outdent,indent,undo,redo,|,link,unlink,image,code,insertdate,inserttime,forecolor,backcolor",
//        theme_advanced_buttons3 : "tablecontrols,|,hr",
//        //theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
//        theme_advanced_toolbar_location : "top", 
//        theme_advanced_toolbar_align : "left",
//        theme_advanced_statusbar_location : "bottom",
//        theme_advanced_resizing : true,
//            
//        // Example content CSS (should be your site CSS)
//        content_css : "css/content.css",
//
//        // Drop lists for link/image/media/template dialogs
//        template_external_list_url : "lists/template_list.js",
//        external_link_list_url : "lists/link_list.js",
//        external_image_list_url : "lists/image_list.js",
//        media_external_list_url : "lists/media_list.js",
//
//        // Replace values for the template plugin
//        template_replace_values : {
//            username : "Kalifast",
//            staffid : "991234"
//        }
//    });


?>




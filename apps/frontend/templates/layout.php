<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <meta http-equiv="X-UA-Compatible" content="IE=7" />
    <head>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <?php include_title() ?> 
        <link rel="Kalifast icon" href="/images/logos/picto_compose_2.png" />
          
        <?php include_stylesheets() ?>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
                    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
                    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <?php //use_helper('jQuery'); ?>
        <!-- start: JavaScript-->
        <!--[if !IE]>-->

        <script src="/js/jquery-2.1.1.min.js"></script>

        <!--<![endif]-->

        <!--[if IE]>
        
                <script src="/js/jquery-1.11.1.min.js"></script>
        
        <![endif]-->

        <!--[if !IE]>-->

        <script type="text/javascript">
                window.jQuery || document.write("<script src='/js/jquery-2.1.1.min.js'>"+"<"+"/script>");
                
        </script>

        <!--<![endif]-->

        <!--[if IE]>
        
                <script type="text/javascript">
                window.jQuery || document.write("<script src='/js/jquery-1.11.1.min.js'>"+"<"+"/script>");
                </script>
                
        <![endif]-->
        <script src="/js/jquery-migrate-1.2.1.min.js"></script>
        <script src="/js/plugins/jquery-ui/js/jquery-ui-1.10.4.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/jquery.blockUI.js"></script>
        <script src="/js/global/eiService.js"></script>
<!--        <script> 
             isPageBlocked=false;
             isPageLoaded=false;
            $(document).ready(function () {
                if(!isPageBlocked && !isPageLoaded) {
                    $.blockUI();
                    isPageBlocked=true; 
                }
                $(window).load(function() {
                    isPageLoaded=true;
                    if(isPageBlocked && isPageLoaded){
                        //insert all your ajax callback code here. 
                        //Which will run only after page is fully loaded in background. 
                        $.unblockUI();
                        $(".blockUI").fadeOut("slow"); 
                    } 
              });

            });
            
        </script>-->
        
    </head>
    <body  >
        <?php if (!isset($projet)) $projet = null;  ?>
        <?php
        //On réccupère éventuellement les attributs du projet courant s'ils sont définis
        $current_project_ref = $sf_user->getAttribute("current_project_ref");
        $current_project_id = $sf_user->getAttribute("current_project_id");
        //S'ils ne sont pas définis , on regarde dans les paramètres de requête
        if ($current_project_ref == null || $current_project_id == null):
            $current_project_ref = $sf_request->getParameter('project_ref');
            $current_project_id = $sf_request->getParameter('project_id');
        endif;

        if ($current_project_ref != null && $current_project_id != null): 
        //On vérifie qu'un profil est éventuellement sélectionné en session utilisateur
            $current_profile_ref = $sf_user->getAttribute('current_profile_ref');
            $current_profile_id = $sf_user->getAttribute('current_profile_id');
            $current_profile_name = $sf_user->getAttribute('current_profile_name');

        //S'ils ne sont pas définis , on regarde dans les paramètres de requête
            if ($current_profile_ref == null || $current_profile_id == null || $current_profile_name == null):
                $current_profile_ref = $sf_request->getParameter('profile_ref');
                $current_profile_id = $sf_request->getParameter('profile_id');
                $current_profile_name = $sf_request->getParameter('profile_name');

                if ($current_profile_ref == null || $current_profile_id == null):
                    //L'url ne contient pas le profil, on recherche le profil par défaut du projet
                    $default_profile = Doctrine_Core::getTable('EiProfil')->getDefaultProjectProfile(
                            $current_project_ref, $current_project_id);
                    if ($default_profile != null):
                        $current_profile_ref = $default_profile->getProfileRef();
                        $current_profile_id = $default_profile->getProfileId();
                        $current_profile_name = $default_profile->getName();
                    endif;
                endif;
                if ($current_profile_name == null && $current_profile_id!=null && $current_profile_ref!=null ): // on vérifie que le nom du profil est en session , auquel cas on le crée
                    //Si le profil sur l'url existe en base
                    $ei_profile = Doctrine_Core::getTable('EiProfil')->findOneByProfileIdAndProfileRef(
                            $current_profile_id, $current_profile_ref);
                    if ($ei_profile != null): $current_profile_name = $ei_profile->getName();
                    endif;

                endif;
            endif;
        endif;
        ?>
        <div id="header" > 
            <?php
            include_partial('global/header',array(
                'project_id' => $current_project_id,
                'project_ref' => $current_project_ref,
                'profile_id' => (isset($current_profile_id) ? $current_profile_id : null),
                'profile_ref' => (isset($current_profile_ref) ? $current_profile_ref : null),
                'profile_name' => (isset($current_profile_name) ? $current_profile_name : null)
            ));
            ?>  
        </div>
        
        <!-- start: Container -->
        <div class="container-fluid content">
            
            <div class="row " id="body_div">  
                <!-- start: Content -->
                <div class="main ">
                    <?php
                    include_partial("executionStack/executionStack",array(
                        'project_id' => $current_project_id,
                        'project_ref' => $current_project_ref,
                        'profile_id' => (isset($current_profile_id) ? $current_profile_id : null),
                        'profile_ref' => (isset($current_profile_ref) ? $current_profile_ref : null),
                        'profile_name' => (isset($current_profile_name) ? $current_profile_name : null)
                    ));
                    ?>
                    <?php
                    include_partial('global/mainMenu',array(
                        'project_id' => $current_project_id,
                        'project_ref' => $current_project_ref,
                        'profile_id' => (isset($current_profile_id) ? $current_profile_id : null),
                        'profile_ref' => (isset($current_profile_ref) ? $current_profile_ref : null),
                        'profile_name' => (isset($current_profile_name) ? $current_profile_name : null)
                             )) 
                            ?> 
                    <div id="eicontent">
                        <?php include_partial('global/alertBox' , array(  'flash_string' => 'search_global_form' )) ?>
                          <?php   echo $sf_content; ?> 
                    </div>        
                </div>

                <!-- end: Content -->
                
            </div><!--/row-->
            
           <?php include_partial('global/footer',array(
           'project_id' => $current_project_id,
           'project_ref' => $current_project_ref,
           'profile_id' => (isset($current_profile_id) ? $current_profile_id : null),
           'profile_ref' => (isset($current_profile_ref) ? $current_profile_ref : null),
           'profile_name' => (isset($current_profile_name) ? $current_profile_name : null)
            )); ?>

         </div> 
         <!-- end: Container -->
 
        <div class="clearfix"></div>
        
         
        <?php //include_javascripts() ?>

        <script src="/js/plugins/pace/pace.min.js"></script> 
        <script src="/js/jquery.caretposition.js"></script>
        <script src="/js/jquery.sew.js"></script>
        <script src="/js/jquery.cookie.js"></script>
        <script src="/js/plugins/attrchange/attrchange.js"></script>
        <script src="/js/moment.js"></script>
        <script src="/js/bootstrap-datetimepicker.min.js"></script>
        <script src="/js/tinymce/tinymce.min.js"></script>
        <script src="/js/tinymce/jquery.tinymce.min.js"></script>
         
        <script src="/js/typeahead.bundle.min.js"></script>
        <script src="/js/typeahead.jquery.js"></script>
        <script src="/js/bootstrap-combobox.js"></script>
        <script src="/js/bootstrap-fileupload.min.js"></script>
        <script src="/js/fileuploader.js"></script>

        <!--page scripts--> 

        <script src="/js/plugins/datatables/js/jquery.dataTables.min.js"></script>
        <script src="/js/plugins/datatables/js/dataTables.bootstrap.min.js"></script>


        <!--theme scripts-->  
        <script src="/js/jquery.mmenu.min.js"></script>
        <script src="/js/custom.min.js"></script>
        <script src="/js/core.min.js"></script>


        <!--inline scripts related to this page-->  
        <script src="/js/pages/table.js"></script>
        <script src="/js/global/eicorps.js"></script>

        <script src="/js/global/eiheader.js"></script> 
        <script src="/js/eiproject/eiproject.js"></script>

        <?php
            // IMPORTANT : Permet de charger dynamiquement les JS en fonction de l'utilisation du jstree et de son mode d'affichage par exemple.
            include_javascripts()
        ?> 
        <!-- end: JavaScript-->

        <script src="/js/librairies/socket.io-1.3.5.js"></script>
        <script src="/js/global/eiApplet.js"></script>
        <script src="/js/global/executionStack.js"></script>
         
    </body>
     
</html>

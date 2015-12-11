 <?php if (isset($ei_project) && isset($ei_profile)):  
    include_partial('einode/getRootDiagram', array('ei_project' => $ei_project, 'ei_profile' => $ei_profile,
        'root_node' => $ei_project->getRootFolder()));
 endif; ?> 

    

      
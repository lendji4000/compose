<?php
/**
 * Description of component
 *
 * @author Grégory Elhaimer <gregory.elhaimer@gmail.com>
 */
class einodeComponents extends sfComponentsKalifast { 
    
    public function executeGetRootDiagram(sfWebRequest $request) {
        $this->checkProject($request);
        $this->opened_ei_nodes = Doctrine_Core::getTable('EiNodeOpenedBy')
                ->getOpenedNodes($this->getUser()->getGuardUser()->getEiUser(), $this->ei_project->getProjectId(), $this->ei_project->getRefId());
    }

}

?>

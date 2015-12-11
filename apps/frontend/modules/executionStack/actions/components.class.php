<?php

/**
 * Class eitestsetComponents
 */
class executionStackComponents extends sfComponentsKalifast {

    public function executeGetList(sfWebRequest $request){
        /** @var EiExecutionStackTable $tableExecutionStack */
        $tableExecutionStack = Doctrine_Core::getTable("EiExecutionStack");

        $this->project_id = $request->getParameter("project_id");
        $this->project_ref = $request->getParameter("project_ref");
        $this->profile_id = $request->getParameter("profile_id");
        $this->profile_ref = $request->getParameter("profile_ref");
        $this->profile_name = $request->getParameter("profile_name");

        $this->stackList = $tableExecutionStack->getUserList($this->getUser()->getEiUser()->getUserId());
    }
}
?>
<?php

/**
 * Created by PhpStorm.
 * User: Geoffroy
 * Date: 03/12/2015
 * Time: 15:30
 */
class GetFunctionDescAndNoticeAction extends sfActions
{
    /**
     * @var SfGuardUser
     */
    private $user;

    public function preExecute()
    {
        /** @var EiUserTable $table */
        $table = Doctrine_Core::getTable('EiUser');
        $this->token = $this->getRequest()->getParameter("token");
        $this->user = $table::getInstance()->getUserByTokenApi($this->token);

        $this->forward404If(is_bool($this->user) && $this->user === false, "You are not allowed to access this page." );
        $this->user = $this->user->getGuardUser();
    }

    /**
     * Action permettant de retourner la description et les notices d'une fonction.
     * En POST, le matching est directement réalisé.
     *
     * @param sfWebRequest $request
     */
    public function execute($request)
    {
        $this->setLayout(false);
        // On détermine le type de contenu retourné.
        $this->getResponse()->setContentType('application/json');

        $this->getUser()->signIn($this->user, true);

        // Récupération du JDT.
        $test_set_id = $request->getParameter("ei_test_set_id");
        $testSet = Doctrine_Core::getTable('EiTestSet')->find($test_set_id);
        // Récupération de la fonction.
        $function_id = $request->getParameter("function_id");
        $function_ref = $request->getParameter("function_ref");
        // Récupération du projet.
        $project_id  = $request->getParameter("project_id");
        $project_ref = $request->getParameter("project_ref");
        $project = Doctrine_Core::getTable('EiProjet')->findOneByProjectIdAndRefId($project_id,$project_ref);
        // Récupération du profil.
        $profile_id = $request->getParameter("profile_id");
        $profile_ref = $request->getParameter("profile_ref");
        /** @var EiProfil $profile */
        $profile = Doctrine_Core::getTable('EiProfil')->findOneByProfileIdAndProfileRefAndProjectIdAndProjectRef($profile_id,$profile_ref,$project_id,$project_ref);

        //Recherche des paramètres de profil à utiliser pour interpreter des éventuels paramètres variables
        $profileParams = $profile->getParamsWithName($this->user->getEiUser());
        //Récupération de l'oracle du jeu de test ( notice  du jeu de test)
        $oracle = $testSet->getTestSetOracle($project,$project->getDefaultNoticeLang(),$function_id,$function_ref, false);
        $oracle = isset($oracle[0]) ? $oracle[0]:$oracle;
        // Récupération des paramètres d'entrée.
        $params = Doctrine_Core::getTable('EiTestSetParam')->getParamForTestSetAndEiTestFunction($test_set_id,$oracle['ei_test_set_function_id']);
        $paramsOut = Doctrine_Core::getTable('EiFunctionHasParam')->findByFunctionRefAndFunctionIdAndParamType($function_ref, $function_id, 'OUT');
        // Transformation de la notice
        $noticeDesc = MyFunction::parseAndExtractParamsValue($oracle["description"], $params, $profileParams);
        $noticeExp = MyFunction::parseAndExtractParamsValue($oracle["expected"], $params, $profileParams);

        if( $paramsOut != null && $paramsOut->count() > 0 ){
            $tmpParams = array();

            /** @var EiFunctionHasParam $param */
            foreach( $paramsOut as $param){
                $tmpParams[] = array(
                    "name" => $param->getName(),
                    "value" => $param->getDefaultValue()
                );
            }

            $paramsOut = $tmpParams;
        }
        else{
            $paramsOut = array();
        }

        return $this->renderText(json_encode(array(
            "func_desc" => $oracle["func_desc"],
            "notice_desc" => $noticeDesc,
            "notice_expected" => $noticeExp,
            "in_params" => $params,
            'out_params' => $paramsOut
        )));
    }

}
<?php

/**
 * Class GenerateExcelRequestAction
 */
class GenerateExcelRequestAction extends sfActionsKalifast
{
    /**
     * Action permettant de générer une requête par l'utilisateur courant pour excel. Par exemple, consulter un jeu de
     * données, des logs, etc.
     *
     * @param sfWebRequest $request
     */
    public function execute($request)
    {
        $this->setLayout(false);
        $this->getResponse()->setContentType('application/json');

        $this->checkProject($request);
        $this->checkProfile($request, $this->ei_project);

        $error = false;
        $success = true;

        try{
            $request->setParameter("ei_user", $this->user);

            /** @var EiDataSetTable $tableDs */
            $tableDs = Doctrine_Core::getTable("EiDataSet");

            /** @var EiUser $user */
            $user = $this->getUser()->getGuardUser()->getEiUser();

            // Vérification des paramètres nécessaires.
            $preserveOriginal = $request->getPostParameter("preserve_original");
            $preserveOriginal = $preserveOriginal == null ? false:($preserveOriginal == "true" ? true:false);
            $data_set_id = $request->getPostParameter("data_set_id");
            $test_set_id = $request->getPostParameter("test_set_id");

            // Création de l'objet.
            $eRequest = new EiExcelRequests();

            $eRequest->setUserId($user->getUserId());
            $eRequest->setUserRef($user->getRefId());

            $eRequest->setProjectRef($this->project_ref);
            $eRequest->setProjectId($this->project_id);

            $eRequest->setProfileRef($this->profile_ref);
            $eRequest->setProfileId($this->profile_id);

            // S'il s'agit d'un jeu de données, on le cherche et on traite la requête.
            if( $data_set_id != null ){
                $data_set = $tableDs->getDataSet($data_set_id, !$preserveOriginal);

                // Si le jeu de données est bien reconnu, on crée la requête puis on la sauvegarde.
                /** @var EiDataSet $data_set */
                if( $data_set != null )
                {
                    $eRequest->setEiDataSet($data_set);
                    $eRequest->save();

                    $success = $eRequest->getId();
                }
                else{
                    $success = false;
                    $error = "We are not able to create an excel request for defined data set.";
                }
            }
            // S'il s'agit d'un jeu de test, on le cherche et on le traite.
            elseif( $test_set_id != null ){
                $test_set = Doctrine_Core::getTable("EiTestSet")->find($test_set_id);

                if( $test_set != null ){
                    $eRequest->setEiTestSet($test_set);
                    $eRequest->save();

                    $success = $eRequest->getId();
                }
                else{
                    $success = false;
                    $error = "We are not able to create an excel request for defined test set.";
                }
            }
            // Sinon, il s'agit d'une requête non souhaitée.
            else{
                $success = false;

                $error = "Your request is invalid !";
            }

        }
        catch( Exception $exc ){
            return $this->renderText(json_encode(array(
                "error" => "Une anomalie s'est produite dans la récupération du document Excel.",
                "success" => false
                ))
            );
        }

        return $this->renderText(json_encode(array("error" => $error, "success" => $success)));
    }
}

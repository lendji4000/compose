<?php

/**
 * EiViewTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiViewTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object EiViewTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EiView');
    }
    
    public function getRootView($project_ref,$project_id ){
        return Doctrine_Core::getTable('EiTree')->findOneByIsRootAndProjectIdAndProjectRefAndType(
                true,$project_id,$project_ref,'View');
    }
    //Rechargement des éléments de type EiView pour un projet 
    public function reload($projets,$project_id, $project_ref, Doctrine_Connection $conn = null) {
        if ($conn == null)
            $conn = Doctrine_Manager::connection();
        //Création de la collection d'objet View à ajouter
        $collection = new Doctrine_Collection("EiView");
        
        $items = $projets->getElementsByTagName("ei_views");
        if ($items->length > 0) {//ya t-il des éléments à traiter?
            $ei_views = $items->item(0)->getElementsByTagName("ei_view");


            if($ei_views->length > 0){
                foreach ($ei_views as $ei_view) {

                    $view_id = $ei_view->getAttribute("view_id");
                    $view_ref = $ei_view->getAttribute("view_ref");
                    //recherche du profil en base
                    if ($view_id != null && $view_ref!=null) {
                        $q = Doctrine_Core::getTable('EiView')->findOneByViewIdAndViewRef($view_id, $view_ref);
                        if ($q && $q != null) {//si l'element existe , on fait une mise à jour
                            $q->setDescription($ei_view->getElementsByTagName("description")->item(0)->nodeValue);
                            $q->setIsActive($ei_view->getElementsByTagName("is_active")->item(0)->nodeValue); 
                            $q->save($conn); 
                        }else{
                        //l'élément n'existe pas encore, et dans ce cas on le crée
                        $new_ei_view = new EiView();
                        $new_ei_view->setViewId($view_id);
                        $new_ei_view->setViewRef($view_ref);
                        $new_ei_view->setDescription($ei_view->getElementsByTagName("description")->item(0)->nodeValue);
                        $new_ei_view->setIsActive($ei_view->getElementsByTagName("is_active")->item(0)->nodeValue);
                        $new_ei_view->setProjectId($project_id);
                        $new_ei_view->setProjectRef($project_ref);
//                        $new_ei_view->save($conn);
                        $collection->add($new_ei_view);
                        }
                    }
                }
                if($collection->getFirst()) $collection->save($conn); //Sauvegarde de la collection
                return 1;
            }
            return null; //On a retrouvé aucun élément de ce type
        }
    }
    
    
    public function insertJsonFunction($arraytab, Doctrine_Connection $conn = null) {
        if ($conn == null)
            $conn = Doctrine_Manager::connection();
        //Si l'id du noeud ou du projet n'est pas renseigné 
        if (!isset($arraytab['v_id']) || !isset($arraytab['v_ref']) || !isset($arraytab['p_id']) || !isset($arraytab['p_ref']) )
            return null;
        $stmt = $conn->prepare("INSERT INTO ei_view (view_id,view_ref, project_id, project_ref,description,is_active,delta,created_at,updated_at) "
                . "VALUES (:view_id,:view_ref, :project_id, :project_ref,:description,:is_active,:delta,:created_at,:updated_at) "
                . "ON DUPLICATE KEY UPDATE view_id=view_id ,view_ref=view_ref ,created_at=:created_at,updated_at=:updated_at");
        
        $stmt->bindValue("view_id", $arraytab['v_id']);
        $stmt->bindValue("view_ref", $arraytab['v_ref']);
        $stmt->bindValue("project_id", $arraytab['p_id']);
        $stmt->bindValue("project_ref", $arraytab['p_ref']);
        $stmt->bindValue("description", $arraytab['v_desc']);
        $stmt->bindValue("is_active", $arraytab['v_act']);
        $stmt->bindValue("delta", $arraytab['v_delt']);
        $stmt->bindValue("created_at", $arraytab['v_creat']);
        $stmt->bindValue("updated_at", $arraytab['v_updat']);
        $stmt->execute(array());
        return 1;
    }
    
    /* Suppression des vues d'un projet donné */
    public function deleteProjectViews($project_id, $project_ref , Doctrine_Connection $conn = null){ 
        if ($conn == null)  $conn = Doctrine_Manager::connection();
            $conn->getTable('EiView')->createQuery('v')
                ->delete()
                ->where('v.project_id=? And v.project_ref=?',
                        array($project_id,$project_ref)) 
                ->execute(); 
    }
}
<?php
/**
 * Représente une collection de formulaire. Cette classe permet d'abstraire le comportement
 * des collections de formulaires imbriqués.
 *
 * @author Grégory Elhaimer
 */
class CollectionForm extends sfForm{
    protected
    //tableau d'identifiants relatifs aux objets à supprimer
    $toDelete = array();
    
    
    /**
     * Ajoute une valeur d'identifiant à supprimer.
     * La fonction ne vérifie pour l'instant pas la présence de l'identifiant dans la collection.
     * @param type $id
     * @throws InvalidArgumentException
     */
    protected function addFormToDelete($id){
        if(isset($id) && $id > 0){
            foreach($this->toDelete as $d=> $td)
                if($id == $td) 
                   throw new InvalidArgumentException('Id déja présent dans la table de suppression.');
            $this->toDelete[$id] = $id;
        }
        else
            throw new InvalidArgumentException("Argument invalide.");        
    }
    
    /**
     * Ajoute un formulaire du nom $name à la collection
     * Cette méthode peut être assimilée à la méthode plus classique : embedForm
     * Cependant elle prend en compte le besoin de suppression d'un element de la
     * liste.
     * 
     * @param type $name
     * @param type $form
     * @param boolean $delete
     * @throws InvalidArgumentException 
     */
    protected function addEmbedForm($name, $form, $delete = false){
        if (isset($name)){
            $this->embedForm($name, $form);
            if(!$form->getObject()->isNew() && $delete == true){
                $this->addFormToDelete ($form->getObject()->getId());
            }
        }
        else
            throw new InvalidArgumentException('No name given for addEmbedVersionForm.');
    }    
    
    /**
     * Supprimer les objets enregistrés dans le tableau $this->toDelete
     * 
     * @param type $tableName le nom de la table où l'objet à supprimer se trouve
     */
    public function deleteSelectedObjects($tableName){
        if(count($this->toDelete) > 0){            
            foreach($this->toDelete as $d => $idObject){
                $object = Doctrine_Core::getTable($tableName)->find($idObject);
                if($object) $object->delete();
            }
        }
    }
}

?>

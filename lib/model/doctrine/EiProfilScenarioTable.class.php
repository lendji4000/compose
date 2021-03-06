<?php

/**
 * EiProfilScenarioTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class EiProfilScenarioTable extends Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object EiProfilScenarioTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('EiProfilScenario');
    }
    
    public function getProfilScenario(){
         return $this->getInstance()->createQuery('ps');
    }

     public function getProfilScenarioByCriteria($profile_id,$ei_scenario_id,$ei_version_id,$created_at,$updated_at){
        $q=$this->getProfilScenario();

        if( $q->execute()->getFirst()) {// existe t-il des Scenarios dans la base?

            //Profil spécifique
            if($profile_id !=null) {
                $q=$q->andWhere('ps.profile_id = ?', $profile_id);
            }

            //profils  pour un scénario spécifique
            if($ei_scenario_id !=null) {
                $q=$q->andWhere('ps.ei_scenario_id= ?', $ei_scenario_id);
            }

            //profils  pour une version donnée
            if($ei_version_id !=null) {
                $q=$q->andWhere('ps.ei_version_id= ?', $ei_version_id);
            }

            //profils créées à une date donnée
            if($created_at !=null) {
                $q=$q->andWhere('ps.created_at = ?', $created_at);
            }

            //profils par date de mise à jour
            if($updated_at !=null) {
                $q=$q->andWhere('ps.updated_at= ?', $updated_at);
            }
            // retour de la requete
            $q=$q->orderBy('ps.created_at');
            return $q;
        }
        return null;
    }
}
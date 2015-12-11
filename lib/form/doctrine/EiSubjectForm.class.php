<?php

/**
 * EiSubject form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiSubjectForm extends BaseEiSubjectForm {

    public function configure() {
        unset($this['created_at'], $this['updated_at']);
        /* La construction du formulaire dépend du projet . 
         * Si le projet n'est pas renseigné et que le sujet est nouveau, on renvoi une exception
         */
        //Récupération du guardUser
        $guardUser = $this->getOption('guardUser');
        if (!$this->getOption('ei_project') && $this->isNew())
            throw new Exception('Echec de la construction du formulaire. project manquant');
        if ($this->isNew()) {
            $ei_project = $this->getOption('ei_project');
            $project_id = $ei_project->getProjectId();
            $project_ref = $ei_project->getRefId();
        } else {
            $ei_project=$this->getObject()->getEiProject();
            $project_id = $this->getObject()->getProjectId();
            $project_ref = $this->getObject()->getProjectRef();
        }


        unset($this['created_at'], $this['updated_at'], $this['author_id']);
        $this->widgetSchema['project_id'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['project_ref'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['package_id'] = new sfWidgetFormInputHidden();
        $this->widgetSchema['package_ref'] = new sfWidgetFormInputHidden();
        //Récupération des livraisons d'un projet
        $this->widgetSchema['delivery_id'] = new sfWidgetFormDoctrineChoice(
                array('model' => 'EiDelivery', 'multiple' => false,
                      'query' => Doctrine_Core::getTable('EiDelivery')
                                    ->getOpenDeliveriesQuery($ei_project),
                      'add_empty' => true, 'order_by' => array('name', 'asc')));

        //Récupération des statuts de sujet d'un projet 
        $this->widgetSchema['subject_state_id'] = new sfWidgetFormDoctrineChoice(
                array('model' => 'EiSubjectState', 'multiple' => false,
                      'query' => Doctrine_Core::getTable('EiSubjectState')
                                    ->getSubjectStateForProjectQuery($project_id, $project_ref),
                      'add_empty' => false));

        //Récupération des priorité de sujet d'un projet 
        $this->widgetSchema['subject_priority_id'] = new sfWidgetFormDoctrineChoice(
                array('model' => 'EiSubjectPriority', 'multiple' => false,
            'query' => Doctrine_Core::getTable('EiSubjectPriority')
                    ->getSubjectPriorityForProjectQuery($project_id, $project_ref),
            'add_empty' => false));
        //Récupération des types de sujet d'un projet 
        $this->widgetSchema['subject_type_id'] = new sfWidgetFormDoctrineChoice(
                array('model' => 'EiSubjectType', 'multiple' => false,
            'query' => Doctrine_Core::getTable('EiSubjectType')
                    ->getSubjectTypeForProjectQuery($project_id, $project_ref),
            'add_empty' => false));

        //Widget description
        $this->widgetSchema['description']->setAttribute('class', 'subjectDescription form-control col-lg-8 col-md-8 col-sm-8');
         
        $this->widgetSchema['description']->setAttribute('placeholder', 'Description ...');
        $this->widgetSchema['name']->setAttribute('class', ' form-control col-lg-8 col-md-8 col-sm-8' );
        $this->widgetSchema['subject_state_id']->setAttribute('class', ' form-control col-lg-8 col-md-8 col-sm-8' );
        $this->widgetSchema['subject_type_id']->setAttribute('class', ' form-control col-lg-8 col-md-8 col-sm-8' );
        $this->widgetSchema['alternative_system_id']->setAttribute('class', ' form-control col-lg-8 col-md-8 col-sm-8' );
        $this->widgetSchema['subject_priority_id']->setAttribute('class', ' form-control col-lg-8 col-md-8 col-sm-8' );
        $this->widgetSchema['delivery_id']->setAttribute('class', ' form-control col-lg-8 col-md-8 col-sm-8' );
        $this->widgetSchema['development_estimation']->setAttribute('class', ' form-control col-lg-8 col-md-8 col-sm-8' );
        $this->widgetSchema['test_estimation']->setAttribute('class', ' form-control col-lg-8 col-md-8 col-sm-8' );
        $this->widgetSchema['expected_date'] = new sfWidgetFormInputText(array(), 
              array('class' => 'form-control col-lg-8 col-md-8 col-sm-8',
                    'data-format'=>"yyyy/MM/dd")); 
        $ei_bug_context = $this->getOption('ei_bug_context');
        
        if($ei_bug_context instanceof EiBugContext ):
            $this->embedForm('ei_bug_context', new EiBugContextForm($ei_bug_context));
        endif;
        
    }

    public function saveEmbeddedForms($con = null, $forms = null) {
        $subject_id = $this->getObject()->getId(); 
        if (null === $con) {
            $con = $this->getConnection();
        }

        if (null === $forms) {
            $forms = $this->embeddedForms;
        } 
        foreach ($forms as $form) {   
                if ($form instanceof sfFormObject) :  
                    $form->getObject()->setSubjectId($subject_id);
                    $form->getObject()->save($con);
                    $form->saveEmbeddedForms($con);
                    else :
                    $this->saveEmbeddedForms($con, $form->getEmbeddedForms()); 
                endif; 
        }
    }

}

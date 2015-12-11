<?php

/**
 * EiDataSetTemplate form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiDataSetTemplateForm extends BaseEiDataSetTemplateForm
{
    /** @var sfLogger */
    private $logger;

    public function configure()
    {
        $this->setWidgets(array(
            'name' => new sfWidgetFormInput(array(), array('class' => 'form-control')),
            'description' => new sfWidgetFormTextarea(array(), array('class' => 'form-control')),
            'ei_data_set_ref_id' => new sfWidgetFormInputHidden()
        ));

        $this->setValidators(array(
            'name'=> new sfValidatorString(),
            'description' => new sfValidatorString(array('required' => false)),
            'ei_data_set_ref_id' => new sfValidatorInteger(array('required' => true))
        ));

        $this->widgetSchema->setNameFormat('ei_data_set_template[%s]');

        $this->parent_node = $this->getOption('ei_node_parent');

        if( $this->isNew() ){
            unset($this["ei_data_set_ref_id"]);

            $dataSet = new EiDataSet();
//            $dataSet->setEiDataSetTemplate($this->getObject());

            $this->getObject()->setEiDataSet($dataSet);

            $this->embedForm("EiDataSet", new EiDataSetForm($dataSet));
        }

        if($this->parent_node == null && $this->isNew())
            throw new Exception('Le noeud parent doit être passé en paramètre au formulaire EiDataSetTemplateForm.');
    }

    /**
     * @param array $taintedValues
     * @param array $taintedFiles
     */
    public function bind(array $taintedValues = null, array $taintedFiles = null)
    {
        $this->logger = sfContext::getInstance()->getLogger();

        $this->logger->info("----------------------------------------------------------");
        $this->logger->info("---   BIND TEMPLATE FORM");

        $ret = parent::bind($taintedValues, $taintedFiles);

        foreach ($this->embeddedForms as $name => $form) {
            if( isset($this->embeddedForms[$name]) && isset($this->values[$name]) ){
                $this->embeddedForms[$name]->isBound = true;
                $this->embeddedForms[$name]->values = $this->values[$name];
            }
        }

        return $ret;
    }

    /**
     * @param null $conn
     * @return mixed
     */
    public function save($conn = null)
    {
        $this->logger = sfContext::getInstance()->getLogger();

        $this->logger->info("----------------------------------------------------------");
        $this->logger->info("---   SAVE TEMPLATE");

        /** @var EiDataSetTemplate $template */
        $template = $this->getObject();

        if ($this->getObject()->isNew()) {
            $node = $template->getEiNode();

            $node->setRootId($this->parent_node->getId());
            $node->setProjectId($this->parent_node->getProjectId());
            $node->setProjectRef($this->parent_node->getProjectRef());
        }

        parent::save($conn);
    }

    /**
     * @param null $con
     * @param null $forms
     */
    public function saveEmbeddedForms($con = null, $forms = null)
    {
        $this->logger = sfContext::getInstance()->getLogger();

        $this->logger->info("----------------------------------------------------------");
        $this->logger->info("---   SAVE EMBEDDED TEMPLATE FORM");

        if( $this->isNew() ){
            foreach ($this->embeddedForms as $name => $form) {
                /** @var EiDataSet $object */
                $object = $form->getObject();
                /** @var EiDataSetTemplate $template */
                $template = $this->getObject();

                $object->setEiDataSetTemplate($this->getObject());
                $object->setName($template->getName());

                $node = $object->getEiNode();

                $node->setProjectId($template->getEiNode()->getProjectId());
                $node->setProjectRef($template->getEiNode()->getProjectRef());

                $object->setRootStr($template->getRootStr());

                $form->save($con);

                $template->setEiDataSet($object);
                $template->save($con);
            }
        }

        return parent::saveEmbeddedForms($con, $forms);
    }
}

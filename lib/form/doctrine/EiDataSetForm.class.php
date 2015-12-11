<?php

/**
 * EiDataSet form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiDataSetForm extends BaseEiDataSetForm {
    /** @var sfLogger */
    private $logger;

    public function configure() {
        // TODO: Modifications visant à supprimer les champs Nom & Descriptions pour coller aux templates.
        //vide tous les champs et créer un input d'upload de fichier.
        if($this->isNew())
        {
            $this->setWidgets(array(
                'file' => new sfWidgetFormInputFile()
            ));

            $this->setValidators(array(
                'file' => new sfValidatorFile(array(
                    "required" => false
                ))
            ));

            $this->widgetSchema->setNameFormat('ei_data_set[%s]');
        
        }else{
            $this->setWidgets(array());
            $this->setValidators(array());
            $this->widgetSchema->setNameFormat('ei_data_set[%s]');
        }
        
        $this->parent_node = $this->getOption('ei_node_parent');
        
//        if($this->parent_node == null && $this->isNew())
//            throw new Exception('Le noeud parent doit être passé en paramètre au formulaire EiDataSetForm.');
    }

    public function save($conn = null )
    {
        $this->logger = sfContext::getInstance()->getLogger();

        $this->logger->info("----------------------------------------------------------");
        $this->logger->info("---   SAVE DATA SET FORM");

        parent::save($conn);

        if($this->isNew())
        {
            $file = $this->getValue('file');

            if( $file != null ){
                $filename = 'uploaded_'.sha1($file->getOriginalName());
                $extension = $file->getExtension($file->getOriginalExtension());
                $fullName = sfConfig::get('sf_upload_dir').'/'.$filename.$extension;
                $file->save($fullName);

                $this->getObject()->createDataLines($fullName);

                unlink($fullName);
            }
            else{
                $this->getObject()->createEmptyDataLines($conn, false);
            }
        }
    }



}

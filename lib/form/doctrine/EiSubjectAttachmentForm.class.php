<?php

/**
 * EiSubjectAttachment form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class EiSubjectAttachmentForm extends BaseEiSubjectAttachmentForm
{
  public function configure()
  {
      unset($this['created_at'],$this['updated_at'],  $this["subject_id"]); 
      $this->widgetSchema["author_id"] = new sfWidgetFormInputHidden();
      $this->widgetSchema["type"] = new sfWidgetFormInputHidden();
      //$this->widgetSchema["subject_id"] = new sfWidgetFormInputHidden(); 
      $this->widgetSchema['path'] = new sfWidgetFormInputFile(array( 
        'label' => 'file',
      ));
      $this->widgetSchema['filename'] = new sfWidgetFormInputHidden();
      
      $this->widgetSchema['description'] = new sfWidgetFormTextarea(array(),
              array('placeholder' => 'Enter comment for attachment',
                     'rows' => 1,
                      'class' => 'form-control col-lg-12 col-md-12 col-sm-12'));
      
      $this->validatorSchema['filename']=new sfValidatorString(array('required'  => false));
      
      $this->validatorSchema['path'] = new sfValidatorFile(array(
        'required'   => false,
        'path'       => sfConfig::get('sf_upload_dir').'/subjectAttachements',
        //'mime_types' => array('application/zip'),
        'max_size' => 5000000  //5Mo maximum 
      ), array('mime_types' => 'Invalid mime type . Try again ...'));
       
      //Post validator pour se rassurer qu'en cas de renseignement du filename , que le path soit également renseigné
            $this->validatorSchema->setPostValidator(
                    new sfValidatorCallback(array('callback' => array($this, 'checkFilenameAndPath') 
                        )));
  }
  /* 
   * Si l'utilisateur ajoute un fichier attaché, 
   * on lui demande également un nom pour le fichier en question 
   */
  function checkFilenameAndPath($validator, $values, $arguments) {  
//        if($values['filename']!=null || $values['path']!=null):
//            if($values['filename']==null)
//                throw new sfValidatorError($validator, 'Please enter filename or empty input...');
//            if($values['path']==null)
//                throw new sfValidatorError($validator, 'Please select a file or empty input...');
//        endif; 
        
        return $values;
    } 
     
   
}

<?php

/**
 * KalFunction form.
 *
 * @package    kalifastRobot
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class KalFunctionForm extends BaseKalFunctionForm
{
  public function configure()
  {  
      unset(  $this['created_at'],$this['updated_at'],$this['delta'],$this['deltaf'],$this['is_active'],$this['criticity']);
      
      $this->widgetSchema['function_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['function_ref'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['project_ref'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['project_id'] = new sfWidgetFormInputHidden();
      $this->widgetSchema['name']=new sfWidgetFormInputText(array(),array(
          "class" => "col-lg-12 col-md-12 col-sm-12  form-control"
      ));
      $this->widgetSchema['description']=new sfWidgetFormTextarea(array(),array(
          "class" => "col-lg-12 col-md-12 col-sm-12  form-control"
      ));
      $this->validatorSchema['name']=new sfValidatorString();
      
      if($this->getOption('size')!==null):
        $form = new kalParamCollectionForm(null, array(
          'kalfunction' => $this->getObject(),
          'size'    => $this->getOption('size'),
          'param_type'    => $this->getOption('param_type'),
        ));
       $this->embedForm('kalParams', $form);
       //else: throw new Exception('Error occur during process ...');
      endif; 
      
      $this->setValidator("name", new sfValidatorAnd(
                array(
            new sfValidatorString(array('required' => true, 'trim' => true), array('required' => 'Empty Name'), array('min_length' => 1)),
            new sfValidatorRegex(
                    array('pattern' => '/^[\w]+[\w\s]*[\w]$/i'), array('invalid' => 'Node name mustn\'t contain special chars.')
            )
                )
        ));
  }
  //Surcharge de la méthode bind pour faire correspondre les données des formulaires de paramètre imbriqués
  public function bind(array $taintedValues = null, array $taintedFiles = null){

    $new_occurrences = new BaseForm();
    if(isset($taintedValues['kalParams'])):
        foreach($taintedValues['kalParams'] as $key => $new_occurrence){ 

          $kalparam = new EiFunctionHasParam();
          $kalparam->setKalFunction($this->getObject())  ;  
          $occurrence_form = new EiFunctionHasParamForm($kalparam); 
          $new_occurrences->embedForm($key,$occurrence_form);
        }

        $this->embedForm('kalParams',$new_occurrences);
    endif;
    
    parent::bind($taintedValues, $taintedFiles);
  }
}

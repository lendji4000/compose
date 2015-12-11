<?php 
class kalParamCollectionForm extends sfForm
{
  public function configure()
  {
    if (!$kalfunction = $this->getOption('kalfunction'))
    {
      throw new InvalidArgumentException('You must provide a function object.');
    }
    if (!$param_type = $this->getOption('param_type'))
    {
      throw new InvalidArgumentException('You must provide a Type for parameter.');
    }
    
    for ($i = 0; $i <= $this->getOption('size'); $i++)
    {
      $kalparam = new EiFunctionHasParam();
      $kalparam->setKalFunction($kalfunction)  ;
      $kalparam->setParamType($param_type)  ;
      $form = new EiFunctionHasParamForm($kalparam);
 
      $this->embedForm($i, $form);
    }
  }
}
?>
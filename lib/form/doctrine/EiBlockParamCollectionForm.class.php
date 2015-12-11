<?php

/**
 * Class EiBlockParamCollectionForm
 */
class EiBlockParamCollectionForm extends sfForm
{
    /**
    * @see EiScenarioStructureForm
    */
    public function configure()
    {
        if (!$block = $this->getOption('block'))
        {
          throw new InvalidArgumentException('You must provide a block object.');
        }

        $parentFormName = $this->getOption('parentName');

        $elements = $this->getOption("elements");

        if( !$elements ){
            $elements = array();
        }

        for ($i = 0; $i < $this->getOption('size'); $i++)
        {
            if( isset($elements[$i]) ){
                $blockParam = $elements[$i];
            }
            else{
                $blockParam = new EiBlockParam();
                $blockParam->setEiVersionStructureParent($block);
                $blockParam->setEiVersionId($block->getEiVersionId());
            }

            $form = new EiBlockParamForm($blockParam);

            if( $parentFormName ){
            }

            $this->embedForm($i, $form);
        }
    }
}

?>
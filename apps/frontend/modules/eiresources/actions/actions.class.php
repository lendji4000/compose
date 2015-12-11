<?php

/**
 * eiresources actions.
 *
 * @package    kalifastRobot
 * @subpackage eiresources
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class eiresourcesActions extends sfActionsKalifast
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  }
  
  public function executeDevices(sfWebRequest $request)
  {
      /* Récuperation des devices disponibles */
      $this->available_devices = EiDeviceTable::getAvailablesDevices();
      
      /* Récupération de mes devices */
      $user_id = $this->guard_user->getId();
      $this->my_devices = EiDeviceUserTable::getMyDevices($user_id);
      $device = new EiDeviceUser();
      $device->setOwner($user_id); 
      $this->form = new EiDeviceUserForm($device); 
  }
  
  public function executeCreate(sfWebRequest $request)
  {
      if (!($request->isMethod(sfRequest::POST)  || $request->isXmlHttpRequest())):
        $this->getUser()->setFlash('alert_version_form', array('title' => 'Warning',
            'class' => 'alert-warning',
            'text' => 'Form has been reinitialized. Need posts parameters'));
        $this->redirect($this->generateUrl('resourcesDevices'));
      endif;
      $user_id = $this->guard_user->getId();
      $device = new EiDeviceUser();
      $device->setOwner($user_id);  
      $this->form = new EiDeviceUserForm($device);
      $this->success=false;
      $this->processForm($request, $this->form); 
      /*if(!$this->success):
          $this->html=$this->getPartial('addDevice',array('form'=>$this->form));
           return $this->renderText(json_encode(array(
                  'html' => $this->html, 
                  'success' => $this->success)));
          return sfView::NONE;
      endif;*/
  }
  
  public function executeUpdate(sfWebRequest $request)
  {
      if (!$request->isMethod(sfRequest::POST)):
        $this->getUser()->setFlash('alert_version_form', array('title' => 'Warning',
            'class' => 'alert-warning',
            'text' => 'Form has been reinitialized. Need posts parameters'));
        $this->redirect($this->generateUrl('resourcesDevices'));
      endif;
      $device = new EiDeviceUser();
      $this->form = new EiDeviceUserForm($device);
      $this->processForm($request, $this->form);
  }

  public function executeDisown(sfWebRequest $request)
  {
        $this->forward404Unless($ei_device = Doctrine_Core::getTable('EiDeviceUser')->find(array($request->getUrlParameter('device_id'))), sprintf('Object device does not exist (%s).', $request->getUrlParameter('device_id')));
        $ei_device->delete();

        $this->getUser()->setFlash('alert_form', array('title' => 'Success',
                'class' => 'alert-success',
                'text' => 'Well done ...'));
        $this->redirect($this->generateUrl('resourcesDevices'));
  }
  
  protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()));
        if ($form->isValid()) {
            $form->save();
            $this->success=true;
            $this->getUser()->setFlash('alert_form', array('title' => 'Success',
                'class' => 'alert-success',
                'text' => 'Well done ...'));
            $this->redirect($this->generateUrl('resourcesDevices'));
        }
        else
        {
            $this->success=false;
            $this->getUser()->setFlash('alert_form', array('title' => 'Error',
                'class' => 'alert-danger',
                'text' => 'An error occurred while trying to save this device.'));
            $this->redirect($this->generateUrl('resourcesDevices'));
        }
    }
}

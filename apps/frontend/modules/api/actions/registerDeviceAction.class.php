<?php

/**
 * Class registerDeviceAction
 */
class registerDeviceAction extends sfActions{
    /**
     * Action permettant d'enregistrer ou de mettre à jour automatiquement un device
     *
     * @param sfWebRequest $request
     */
    public function execute($request)
    {
        $this->getResponse()->setContentType('application/json');
        $this->setLayout(false);
        if($request->getPostParameter('drivers') == null)
        {
            $response = array(
                "error" => "Error with the post parameters drivers.",
                "success" => false
            );
        }
        else
        {
            $JSONArray = json_decode($request->getPostParameter('drivers'));
              //die("json: ".$JSONArray. " / error:".json_last_error());
            $device_identifier = $request->getParameter('device_identifier');
            $device_type_name = $request->getParameter('device_type');

            $response = array(
                "error" => "An error occured when we try to create / update the device.",
                "success" => false
            );

            try{
                $device = Doctrine_Core::getTable("EiDevice")->findOneBy("device_identifier", $device_identifier);
                
                if( $device != null )
                {
                    /* Si le device existe, on efface ses drivers (les browsers sont effacés en cascade) */
                    $device_drivers = $device->getEiDeviceDriver();
                    foreach ($device_drivers as $device_driver)
                    {
                        $device_driver->delete();
                    }
                }
                else
                {
                    /* Sinon on créé ce device */
                    $device = new EiDevice();
                    $device->setDeviceIdentifier($device_identifier);
                    $device_type = Doctrine_Core::getTable('EiDeviceType')->findOneBy('hidden_name', $device_type_name);
                    $device->setDeviceTypeId($device_type);
                    $device->save();
                }

                /* On créé les drivers et browsers de ces drivers pour le device */
                foreach ($JSONArray as $driver) {
                    $driver_type_name = $driver->{'driver_type'};
                    $driver_type = Doctrine_Core::getTable("EiDriverType")->findOneBy("hidden_name", $driver_type_name);

                    $device_driver = new EiDeviceDriver();
                    $device_driver->setDeviceId($device->getId());
                    $device_driver->setDriverTypeId($driver_type->getId());
                    $device_driver->save();
                    foreach ($driver->{'browsers'} as $browser_name)
                        {
                            $browser_type = Doctrine_Core::getTable("EiBrowserType")->findOneBy("hidden_name", $browser_name);

                            $driver_browser = new EiDriverBrowser();
                            $driver_browser->setDeviceDriverId($device_driver->getId());
                            $driver_browser->setBrowserTypeId($browser_type->getId());
                            $driver_browser->save();
                        }
                    }

                unset($response["error"]);
                $response["success"] = true;
            }
            catch( Exception $e ){
                $response = array(
                    "error" => "Exception : An error occured when we try to create / update the device: " . $e->getMessage()
                );
            }
        }

        return $this->renderText(json_encode($response));
    }
} 
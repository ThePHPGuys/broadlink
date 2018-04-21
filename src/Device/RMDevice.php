<?php

namespace TPG\Broadlink\Device;


use TPG\Broadlink\Command\GetSensorsCommand;
use TPG\Broadlink\Protocol;

class RMDevice extends AuthenticatedDevice
{

    public function getTemperature(){
        return Protocol::create()->executeCommand(new GetSensorsCommand($this))->current()['temperature'];
    }
}
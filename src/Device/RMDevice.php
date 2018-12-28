<?php

namespace TPG\Broadlink\Device;


use TPG\Broadlink\Command\GetSensorsCommand;
use TPG\Broadlink\Packet\PacketBuilder;
use TPG\Broadlink\Protocol;

class RMDevice extends AuthenticatedDevice
{
    public function getTemperature(){
        /** @var PacketBuilder $result */
        $result = Protocol::create()->executeCommand(new GetSensorsCommand($this))->current();
        return $result->readFloat16(0x4);
    }
}
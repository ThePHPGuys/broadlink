<?php

namespace TPG\Broadlink\Device;



use TPG\Broadlink\Request\CheckSensorsRequest;
use TPG\Broadlink\Response\CheckSensorsResponse;

class RMDevice extends AbstractDevice implements HasTemperatureSensorInterface
{
    public function getTemperature():float {
        /** @var CheckSensorsResponse $response */
        return $this->executeRequest(new CheckSensorsRequest())->getTemperature();
    }
}
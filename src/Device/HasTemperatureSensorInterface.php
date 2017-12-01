<?php

namespace TPG\Broadlink\Device;


interface HasTemperatureSensorInterface
{
    public function getTemperature():float;
}
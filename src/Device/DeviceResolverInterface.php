<?php

namespace TPG\Broadlink\Device;


interface DeviceResolverInterface
{
    public function getDeviceClass(int $deviceId);
}
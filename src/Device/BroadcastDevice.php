<?php

namespace TPG\Broadlink\Device;


final class BroadcastDevice implements DeviceInterface
{
    public function getMac(): string
    {
        return '';
    }

    public function getIP(): string
    {
        return '255.255.255.255';
    }

    public function getDeviceId(): int
    {
        return 0;
    }

    public function getPort(): int
    {
        return 80;
    }

}
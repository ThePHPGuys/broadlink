<?php

namespace TPG\Broadlink;


use TPG\Broadlink\Command\AuthenticateCommand;
use TPG\Broadlink\Command\CommandInterface;
use TPG\Broadlink\Command\DiscoverCommand;
use TPG\Broadlink\Device\AuthenticatedDevice;
use TPG\Broadlink\Device\Device;
use TPG\Broadlink\Device\DeviceInterface;
use TPG\Broadlink\Device\DiscoveredDevice;

class Broadlink
{

    /**
     * Return all devices in current network
     * @return DiscoveredDevice[]
     */
    public static function discover():array {
        $protocol = Protocol::create();
        $discoverCommand = new DiscoverCommand();
        $devices = [];
        foreach($protocol->executeCommand($discoverCommand) as $device){
            $devices[] = $device;
        }
        return $devices;
    }

    public static function authenticate(DeviceInterface $device,$authenticatedClass = AuthenticatedDevice::class):AuthenticatedDevice{
        $protocol = Protocol::create();
        $discoverCommand = new AuthenticateCommand($device,$authenticatedClass);
        return $protocol->executeCommand($discoverCommand)->current();
    }
}
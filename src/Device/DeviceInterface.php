<?php

namespace TPG\Broadlink\Device;


interface DeviceInterface
{
    public const DEFAULT_PORT=80;

    // Commands
    public const COMMAND_DISCOVER=0x06;
    public const COMMAND_AUTHENTICATE=0x65;
    public const COMMAND_GET_INFO=0x6a;



    public function getMac():string;
    public function getIP():string;
    public function getDeviceId():int;
    public function getPort():int;

}
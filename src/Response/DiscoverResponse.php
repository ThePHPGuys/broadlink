<?php

namespace TPG\Broadlink\Response;

use TPG\Broadlink\Packet\PacketBuilder;

class DiscoverResponse implements ResponseInterface
{
    private $packetBuilder;

    public function __construct(PacketBuilder $packetBuilder)
    {
        $this->packetBuilder = $packetBuilder;
    }

    public function getDeviceId():int {
        return $this->packetBuilder->readInt16(0x34);
    }

    public function getIp():string {
        return implode('.',$this->packetBuilder->readBytes(0x36,4));
    }

    public function getMac():string {
        return vsprintf('%02x:%02x:%02x:%02x:%02x:%02x',$this->packetBuilder->readBytes(0x3a,6));
    }

    public function getName(){
        return trim(implode(array_map('\chr',array_reverse($this->packetBuilder->readBytes(0x40,60)))));
    }

}
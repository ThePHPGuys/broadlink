<?php

namespace TPG\Broadlink\Response;


use TPG\Broadlink\Packet\PacketBuilder;

class CheckSensorsResponse implements ResponseInterface
{
    /**
     * @var PacketBuilder
     */
    private $packetBuilder;

    public function __construct(PacketBuilder $packetBuilder)
    {

        $this->packetBuilder = $packetBuilder;
    }

    public function getPacketBuilder(){
        return $this->packetBuilder;
    }

    public function getTemperature(){
        return $this->getPacketBuilder()->readFloat16(0x4);
    }
}
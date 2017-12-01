<?php

namespace TPG\Broadlink\Response;

use TPG\Broadlink\Packet\PacketBuilder;

class AuthenticationResponse implements ResponseInterface
{

    /**
     * @var PacketBuilder
     */
    private $packetBuilder;

    public function __construct(PacketBuilder $packetBuilder)
    {
        $this->packetBuilder = $packetBuilder;
    }

    public function getSessionId(){
        return $this->packetBuilder->readInt32(0x00);
    }

    public function getEncryptionKey(){
        return array_reverse($this->packetBuilder->readBytes(0x04,16));
    }
}
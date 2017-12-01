<?php

namespace TPG\Broadlink\Request;


use TPG\Broadlink\Device\DeviceInterface;
use TPG\Broadlink\Packet\Packet;
use TPG\Broadlink\Packet\PacketBuilder;
use TPG\Broadlink\Response\AuthenticationResponse;
use TPG\Broadlink\Session;

class AuthenticationRequest implements RequestInterface
{

    public function execute(Session $session):AuthenticationResponse
    {
        $responsePacket = $session->sendCommand(DeviceInterface::COMMAND_AUTHENTICATE,Packet::createZeroPacket(0x50));
        return new AuthenticationResponse(new PacketBuilder($responsePacket));
    }


}
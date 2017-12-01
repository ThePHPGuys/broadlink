<?php

namespace TPG\Broadlink\Request;


use TPG\Broadlink\Device\DeviceInterface;
use TPG\Broadlink\Packet\PacketBuilder;
use TPG\Broadlink\Response\CheckSensorsResponse;
use TPG\Broadlink\Session;

class CheckSensorsRequest implements RequestInterface,AuthenticatedRequestInterface
{
    public function execute(Session $session)
    {
        $responsePacket = $session->sendCommand(DeviceInterface::COMMAND_GET_INFO,PacketBuilder::create(0x16)->writeByte(0x00,0x01)->getPacket());
        return new CheckSensorsResponse(new PacketBuilder($responsePacket));
    }

}
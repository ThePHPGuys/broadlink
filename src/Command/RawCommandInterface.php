<?php

namespace TPG\Broadlink\Command;


use TPG\Broadlink\Packet\Packet;

interface RawCommandInterface extends CommandInterface
{
    public function getPacket(): Packet;
}
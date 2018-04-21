<?php
namespace TPG\Broadlink\Command;


use TPG\Broadlink\Packet\Packet;

interface EncryptedCommandInterface extends CommandInterface
{
    public function getPayload():Packet;
}
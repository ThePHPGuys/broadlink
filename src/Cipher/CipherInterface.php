<?php

namespace TPG\Broadlink\Cipher;


use TPG\Broadlink\Packet\Packet;

interface CipherInterface
{
    public function encrypt(Packet $data):Packet;
    public function decrypt(Packet $data):Packet;
}
<?php

namespace TPG\Broadlink\Cipher;


use TPG\Broadlink\Packet\Packet;
use TPG\Broadlink\Utils;

class Cipher implements CipherInterface
{
    /**
     * @var array
     */
    private $key;
    /**
     * @var array
     */
    private $iv;

    /**
     * Cipher constructor.
     */
    public function __construct(array $key,array $iv)
    {

        $this->key = $key;
        $this->iv = $iv;
    }

    private function getKeyString(){
        return Utils::array2string($this->key);
    }

    private function getIvString(){
        return Utils::array2string($this->iv);
    }

    public function encrypt(Packet $data): Packet
    {
        $msg = (string)$data;
        $pad = 16 - (strlen($msg) % 16);
        $msg .= str_repeat(chr(0), $pad);
        return Packet::createFromString(openssl_encrypt($msg,'AES-128-CBC',$this->getKeyString(),OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, $this->getIvString()));
    }

    public function decrypt(Packet $data): Packet
    {
        return Packet::createFromString(openssl_decrypt((string)$data,'AES-128-CBC',$this->getKeyString(),OPENSSL_RAW_DATA|OPENSSL_ZERO_PADDING, $this->getIvString()));
    }


}
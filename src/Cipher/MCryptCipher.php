<?php

namespace TPG\Broadlink\Cipher;


use TPG\Broadlink\Packet\Packet;
use TPG\Broadlink\Utils;

class MCryptCipher implements CipherInterface
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
        return Packet::createFromString(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->getKeyString(), (string)$data, MCRYPT_MODE_CBC, $this->getIvString()));
    }

    public function decrypt(Packet $data): Packet
    {
        return Packet::createFromString(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->getKeyString(), (string)$data, MCRYPT_MODE_CBC, $this->getIvString()));
    }

}
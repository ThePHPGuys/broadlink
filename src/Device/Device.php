<?php

namespace TPG\Broadlink\Device;


use TPG\Broadlink\Cipher\Cipher;
use TPG\Broadlink\Cipher\CipherInterface;

class Device implements DeviceInterface
{

    /**
     * @var string
     */
    private $ip;
    /**
     * @var string
     */
    private $mac;

    public function __construct(string $ip,string $mac)
    {
        $this->ip = $ip;
        $this->mac = $mac;
    }

    public function getMac(): string
    {
        return $this->mac;
    }

    public function getIP(): string
    {
        return $this->ip;
    }

    public function getPort(): int
    {
        return self::DEFAULT_PORT;
    }

    public function getCipher(): CipherInterface
    {
        return new Cipher(self::BASE_KEY,self::BASE_IV);
    }




}
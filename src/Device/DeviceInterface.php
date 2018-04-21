<?php

namespace TPG\Broadlink\Device;


use TPG\Broadlink\Cipher\CipherInterface;

interface DeviceInterface
{
    /**
     * Default device port
     */
    public const DEFAULT_PORT=80;

    /**
     * Default key
     */
    public const BASE_KEY = [0x09, 0x76, 0x28, 0x34, 0x3f, 0xe9, 0x9e, 0x23, 0x76, 0x5c, 0x15, 0x13, 0xac, 0xcf, 0x8b, 0x02];
    /**
     * Default vector
     */
    public const BASE_IV =  [0x56, 0x2e, 0x17, 0x99, 0x6d, 0x09, 0x3d, 0x28, 0xdd, 0xb3, 0xba, 0x69, 0x5a, 0x2e, 0x6f, 0x58];

    /**
     * Device mac address
     * @return string
     */
    public function getMac():string;

    /**
     * Device IP address
     * @return string
     */
    public function getIP():string;

    /**
     * Device port
     * @return int
     */
    public function getPort():int;

    /**
     * Cipher mechanism used for encrypted commands
     * @return CipherInterface
     */
    public function getCipher():CipherInterface;
}
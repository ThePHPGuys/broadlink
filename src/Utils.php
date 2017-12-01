<?php

namespace TPG\Broadlink;


use TPG\Broadlink\Packet\Packet;

class Utils
{
    public static function array2string(array $array):string {
        return (string)implode(array_map('\chr',$array));
    }

    public static function getIPAddressArray(string $ipAddress):array{
        $ipAddressArray = explode('.',$ipAddress);
        foreach ($ipAddressArray as &$i) {
            $i = (int)$i;
        }
        return $ipAddressArray;
    }

    public static  function getMacAddressArray(string $macAddress):array{
        $macAddressArray = explode(':',$macAddress);
        foreach ($macAddressArray as &$m) {
            $m = hexdec($m);
        }
        return $macAddressArray;
    }

}
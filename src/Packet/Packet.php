<?php

namespace TPG\Broadlink\Packet;

use TPG\Broadlink\Utils;

class Packet extends \SplFixedArray
{
    public function __toString()
    {
        return Utils::array2string($this->toArray());
    }

    public function toHexArray(){
        $hexArray = [];
        foreach ($this as $dec){
            $hexArray[] = sprintf('%02x',$dec);
        }
        return $hexArray;
    }

    public static function createFromString(string $data){
        return static::fromArray(unpack('C*', $data));
    }

    public static function fromArray($array, $save_indexes = null)
    {
        $obj = new static(count($array));
        $c=0;
        foreach ($array as $i=>$v){
            $obj[$c] = $v;
            $c++;
        }
        return $obj;
    }

    public static function createZeroPacket($size){
        return static::fromArray(array_fill(0,$size,0));
    }

}
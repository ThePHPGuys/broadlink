<?php

namespace TPG\Broadlink;


use TPG\Broadlink\Device\DeviceInterface;
use TPG\Broadlink\Packet\Packet;

class Connection
{
    private $socket;

    private function open(){
        $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_set_option($this->socket, SOL_SOCKET, SO_BROADCAST, 1);
        return $this;
    }

    public function sendPacketToDeviceArray(Packet $packet,DeviceInterface $device, $timeout=1):\Generator{
        $this->open();
        socket_sendto($this->socket, (string)$packet, $packet->getSize(), 0, $device->getIP(), $device->getPort());
        socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, array('sec'=>$timeout, 'usec'=>0));
        while($response = @socket_read($this->socket, 1024, 0)){
            yield Packet::createFromString($response);
        }
        $this->close();
    }

    private function close(){
        socket_close($this->socket);
    }


}
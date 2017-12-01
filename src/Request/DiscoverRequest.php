<?php

namespace TPG\Broadlink\Request;


use TPG\Broadlink\Device\AbstractDevice;
use TPG\Broadlink\Device\DeviceInterface;
use TPG\Broadlink\Device\DeviceResolverInterface;
use TPG\Broadlink\Packet\Packet;
use TPG\Broadlink\Packet\PacketBuilder;
use TPG\Broadlink\Response\DiscoverResponse;
use TPG\Broadlink\Session;
use TPG\Broadlink\Utils;

class DiscoverRequest implements RequestInterface
{
    /**
     * @var null
     */
    private $localIp;
    /**
     * @var DeviceResolverInterface
     */
    private $deviceResolver;

    public function __construct(DeviceResolverInterface $deviceResolver)
    {
        $this->deviceResolver = $deviceResolver;
    }

    public function setLocalIp($localIp){
        if($localIp!==null and !filter_var($localIp, FILTER_VALIDATE_IP)){
            throw new \InvalidArgumentException('Invalid local IP address');
        }
        $this->localIp = $localIp;
    }

    private function createPacket():Packet{
        $packetBuilder = PacketBuilder::create(0x30);
        $dt = new \DateTime();
        $timeZoneDiff = (int)($dt->format('Z')/3600);
        $packetBuilder->writeInt32(0x08,$timeZoneDiff);
        $packetBuilder->writeInt16(0x0c,(int)$dt->format('Y'));
        $packetBuilder->writeBytes(0x0e,[(int)($dt->format('H')-$timeZoneDiff),(int)$dt->format('i'),(int)$dt->format('s')]);
        $packetBuilder->writeBytes(0x10,[(int)$dt->format('m'), (int)$dt->format('d'), (int)$dt->format('N'),(int)$dt->format('m')]);
        $packetBuilder->writeBytes(0x18,array_reverse(Utils::getIPAddressArray($this->getLocalIp())));
        $packetBuilder->setCommand(DeviceInterface::COMMAND_DISCOVER);
        $packetBuilder->writeChecksum();
        return $packetBuilder->getPacket();

    }

    private function getLocalIp(){
        if(!$this->localIp){
            $s = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
            socket_connect($s ,'8.8.8.8', 53);  // connecting to a UDP address doesn't send packets
            socket_getsockname($s, $localIp);
            socket_close($s);
            $this->localIp = $localIp;
        }

        return $this->localIp;
    }

    public function execute(Session $session)
    {
        $discoverPacket = $this->createPacket();
        $openConnection = $session->getConnection()->open();
        $resultPackets = $openConnection->sendPacketToDeviceArray($discoverPacket,$session->getDevice());
        $openConnection->close();
        $devices = [];
        foreach ($resultPackets as $packet){
            $packetBuilder = new PacketBuilder($packet);
            $devices[] = $this->getDevice($packetBuilder);
        }
        return $devices;
    }

    private function getDevice(PacketBuilder $packet):DeviceInterface{
        $discoverResponse = new DiscoverResponse($packet);
        $class = $this->deviceResolver->getDeviceClass($discoverResponse->getDeviceId());
        if(!$class){
            throw new \Exception('Unknown device '.$discoverResponse->getDeviceId());
        }
        /** @var AbstractDevice $device */
        $device = new $class($discoverResponse->getIp(),$discoverResponse->getMac(),$discoverResponse->getDeviceId());
        $device->setName($discoverResponse->getName());
        return $device;
    }

}
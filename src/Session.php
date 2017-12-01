<?php

namespace TPG\Broadlink;


use TPG\Broadlink\Cipher\CipherInterface;
use TPG\Broadlink\Device\DeviceInterface;
use TPG\Broadlink\Packet\Packet;
use TPG\Broadlink\Packet\PacketBuilder;
use TPG\Broadlink\Request\RequestInterface;

class Session
{
    private $packetId = 0;
    /**
     * @var DeviceInterface
     */
    private $device;

    /**
     * @var int
     */
    private $sessionId;

    private $cipher;

    private $connection;

    public function __construct(DeviceInterface $device, CipherInterface $cipher=null, $sessionId=0)
    {
        $this->device = $device;
        $this->cipher = $cipher;
        $this->sessionId = $sessionId;
        $this->connection = new Connection();
        $this->packetId = random_int(0, 0xffff);
    }

    public function getDevice(): DeviceInterface
    {
        return $this->device;
    }

    public function getConnection():Connection{
        return $this->connection;
    }

    public function executeRequest(RequestInterface $request){
        return $request->execute($this);
    }

    public function sendCommand(int $command,Packet $payload):Packet{
        $sessionPacketBuilder = $this->createSessionPacketBuilder($command);
        $payloadPacketBuilder = new PacketBuilder($payload);

        //Calculate payload checksum
        $sessionPacketBuilder->setPayloadChecksum($payloadPacketBuilder->calculateChecksum());
        //Encrypt and attach payload
        $encryptedPayload = $this->cipher->encrypt($payload);
        $sessionPacketBuilder->attachPayload($encryptedPayload);
        $sessionPacketBuilder->writeChecksum();

        $readyPacket = $sessionPacketBuilder->getPacket();

        $connection = $this->connection->open();
        $responsePacket = $connection->sendPacketToDevice($readyPacket,$this->getDevice());
        $connection->close();

        return $this->extractAndDecodePayload($responsePacket);
    }

    private function createSessionPacketBuilder(int $command){
        $packetBuilder = PacketBuilder::create(0x38);
        $packetBuilder->setCommand($command);
        $packetBuilder->writeInt16(0x28,$this->getPacketId());
        $packetBuilder->writeBytes(0x2a,Utils::getMacAddressArray($this->getDevice()->getMac()));
        $packetBuilder->writeInt32(0x30,$this->sessionId);
        return $packetBuilder;
    }

    private function extractAndDecodePayload(Packet $responsePacket){
        $responsePacketBuilder = new PacketBuilder($responsePacket);
        if($responsePacketBuilder->hasError()){
            throw new \Exception('Error from device');
        }
        $encryptedPayload = $responsePacketBuilder->extractPayload();
        return $this->cipher->decrypt($encryptedPayload);
    }

    private function getPacketId():int{
        return ++$this->packetId;
    }
}
<?php

namespace TPG\Broadlink\Device;


use TPG\Broadlink\Broadlink;
use TPG\Broadlink\Cipher\CipherInterface;

class DiscoveredDevice implements DeviceInterface, \JsonSerializable
{
    /**
     * @var DeviceInterface
     */
    private $device;
    /**
     * @var int
     */
    private $deviceId;
    /**
     * @var string
     */
    private $name;

    public function __construct(DeviceInterface $device,int $deviceId, string $name)
    {
        $this->device = $device;
        $this->deviceId = $deviceId;
        $this->name = $name;
    }

    public function getId():int {
        return $this->deviceId;
    }

    public function getModel(){
        return self::getModelByDeviceId($this->deviceId);
    }
    public function getName(){
        return $this->name;
    }

    public function getMac(): string
    {
        return $this->device->getMac();
    }

    public function getIP(): string
    {
        return $this->device->getIP();
    }

    public function getPort(): int
    {
        return $this->device->getPort();
    }

    public function getCipher(): CipherInterface
    {
        return $this->device->getCipher();
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->getName(),
            'ip' => $this->getIP(),
            'mac' => $this->getMac(),
            'id' => $this->getId(),
            'model' => $this->getModel()
        ];
    }

    private static function getModelByDeviceId($deviceId){
        switch ($deviceId){
            case 0:
                return 'SP1';
            case 0x2711:
                return 'SP2';
            case 0x2719:
            case 0x7919:
            case 0x271a:
            case 0x791a:
                return 'Honeywell SP2';
            case 0x2720:
                return 'SPMini';
            case 0x753e:
                return 'SP3';
            case 0x2728:
                return 'SPMini2';
            case 0x2733:
            case 0x273e:
                return 'OEM branded SPMini';
                break;
            case 0x7530:
            case 0x7918:
                return 'OEM branded SPMini2';
                break;
            case 0x2736:
                return 'SPMiniPlus';
                break;
            case 0x2712:
                return 'RM2';
                break;
            case 0x2737:
                return 'RM Mini';
                break;
            case 0x273d:
                return 'RM Pro Phicomm';
                break;
            case 0x2783:
                return 'RM2 Home Plus';
                break;
            case 0x277c:
                return 'RM2 Home Plus';
            case 0x272a:
                return 'RM2 Pro Plus';
            case 0x2787:
                return 'RM2 Pro Plus2';
            case 0x278b:
                return 'RM2 Pro Plus BL';
            case 0x278f:
                return 'RM Mini Shate';
            case 0x2714:
                return 'A1';
            case 0x4EB5:
            case 0x4EB7:
                return 'MP1';
            case 0x2722:
                return 'S1 (SmartOne Alarm Kit)';
            default:
                return 'Unknown';
        }
    }

    private function getDeviceClass(){
        return AuthenticatedDevice::class;
    }

    public function authenticate(){
        return Broadlink::authenticate($this,$this->getDeviceClass());
    }

}
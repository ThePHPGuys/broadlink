<?php

namespace TPG\Broadlink\Device;


use TPG\Broadlink\Cipher\Cipher;
use TPG\Broadlink\Request\AuthenticatedRequestInterface;
use TPG\Broadlink\Request\AuthenticationRequest;
use TPG\Broadlink\Request\DiscoverRequest;
use TPG\Broadlink\Response\AuthenticationResponse;
use TPG\Broadlink\Session;

abstract class AbstractDevice implements DeviceInterface
{
    private const BASE_KEY = [0x09, 0x76, 0x28, 0x34, 0x3f, 0xe9, 0x9e, 0x23, 0x76, 0x5c, 0x15, 0x13, 0xac, 0xcf, 0x8b, 0x02];
    private const BASE_IV =  [0x56, 0x2e, 0x17, 0x99, 0x6d, 0x09, 0x3d, 0x28, 0xdd, 0xb3, 0xba, 0x69, 0x5a, 0x2e, 0x6f, 0x58];

    /**
     * @var string
     */
    private $ip;
    /**
     * @var string
     */
    private $mac;
    /**
     * @var int
     */
    private $deviceId;
    /**
     * @var string
     */
    private $name;
    /**
     * @var Session
     */
    private $session;

    public function __construct(string $ip,string $mac,$deviceId=null)
    {
        $this->ip = $ip;
        $this->mac = $mac;
        $this->deviceId = $deviceId;
    }

    protected function getSession():Session{
        return $this->session;
    }

    public function getMac(): string
    {
        return $this->mac;
    }

    public function getIP(): string
    {
        return $this->ip;
    }

    public function getDeviceId(): int
    {
        return $this->deviceId;
    }

    public function getPort(): int
    {
        return self::DEFAULT_PORT;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }


    public function getModel(){
        return self::getModelByDeviceId($this->deviceId);
    }

    public static function getModelByDeviceId($deviceId){
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

    public static function discover($localIp=null,DeviceResolverInterface $deviceResolver=null):array {
        $discoverSession = new Session(new BroadcastDevice());

        if(!$deviceResolver){
            $deviceResolver = new DeviceResolver();
        }

        $discoverRequest = new DiscoverRequest($deviceResolver);

        if($localIp){
            $discoverRequest->setLocalIp($localIp);
        }
        return $discoverSession->executeRequest($discoverRequest);
    }

    public function authenticate(){
        $session = new Session($this,new Cipher(self::BASE_KEY,self::BASE_IV));
        /** @var AuthenticationResponse $authenticationResponse */
        $authenticationResponse = $session->executeRequest(new AuthenticationRequest());
        $this->session = new Session($this,new Cipher($authenticationResponse->getEncryptionKey(),self::BASE_IV),$authenticationResponse->getSessionId());
        return true;
    }

    public function executeRequest(AuthenticatedRequestInterface $request){
        if(!$this->session){
            throw new \Exception('Device is not authenticated');
        }
        return $this->session->executeRequest($request);
    }
}
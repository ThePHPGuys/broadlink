<?php

namespace TPG\Broadlink\Device;


class DeviceResolver implements DeviceResolverInterface
{
    public function getDeviceClass(int $deviceId): string
    {
        if ($deviceId === 0) {
            return SP1Device::class;
        }

        if ($deviceId === 0x2714) {
            return A1Device::class;
        }

        if (\in_array($deviceId, [0x4EB5,0x4EF7], true)) {
            return MP1Device::class;
        }

        if (\in_array($deviceId, [0x2712, 0x2737, 0x273d, 0x2783, 0x277c, 0x272a, 0x2787, 0x278b, 0x278f], true)) {
            return RMDevice::class;
        }

        if (
            \in_array($deviceId, [
                0x2711,
                0x2719,
                0x7919,
                0x271a,
                0x791a,
                0x2720,
                0x753e,
                0x947a,
                0x9479,
                0x2728,
                0x2733,
                0x273e,
                0x2736
            ], true)
            or ($deviceId >= 0x7530 and $deviceId <= 0x7918)) {
            return \SP2::class;
        }

        throw new \Exception('Unknown device:'.dechex($deviceId));
    }
}

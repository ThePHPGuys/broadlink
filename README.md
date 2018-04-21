# Broadlink API PHP7 library 

A PHP 7 library for controlling IR and Wireless 433Mhz controllers from [Broadlink](http://www.ibroadlink.com/rm/). 
The protocol refer to [mjg59/python-broadlink](https://github.com/mjg59/python-broadlink/blob/master/README.md)

Currently supported only RM Devices

Discover all devices in network:
```php
echo json_encode(\TPG\Broadlink\Broadlink::discover());
```
Will produce:
```json
[
    {
        "model": "RM2 Pro Plus",
        "name": "Living Room",
        "ip": "192.168.88.15",
        "mac": "34:ea:cc:cc:cc:bc",
        "id": "10026"
        
    },
    {
        "model": "RM2 Pro Plus",
        "name": "Sleeping Room",
        "ip": "192.168.88.14",
        "mac": "34:ea:cc:cc:cc:bf",
        "id": "10026"
    }
]
```

Use already known device:
```php
\TPG\Broadlink\Device\RMDevice::authenticate('192.168.88.15','34:ea:cc:cc:cc:bc')->getTemperature()
```

### Draft implementation of Broadlink Catalog Cloud

```php
use TPG\Broadlink\Cloud\Catalog;

$catalog = new Catalog('/path/where/you/want/to/save/remotes');
$remotes = $catalog->search('Samsung');

//Download first remote
print_r($remotes[0]->download());

//Or download all found remotes
foreach($remotes as $remote){
    $remote->download();
}
```
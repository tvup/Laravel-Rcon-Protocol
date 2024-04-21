# About

All credits to AnvilM (https://github.com/AnvilM/PHP_RCON/tree/main), I upload my own version according to my own requirements, preferences and needs.

Source RCON Protocol library for Laravel

## Install

You can get this package using composer

```bash
composer require rminks/rcon
```

## Configuration

### Setting in valve games (Engine Source)
By default, SRCDS listens for RCON connections on TCP port 27015. If the server's port number is changed using the -port option, the RCON port will change as well. SRCDS will always refuse any RCON connection attempt originating from an IP on its banlist.
If the rcon_password cvar is written to for any reason, the server will immediately close all authenticated RCON connections. This happens even if the new value of rcon_password is identical to the old one. These connections will need to be re-opened and re-authenticated before any further commands can be issued. Connections which have not been authenticated yet are not dropped. If the password was changed remotely, the server will not respond to the command which caused the password to change.
if rcon_password is not setted, the gameserver gonna reject all connections

### Setting in Minecraft
To use RCON on your server, you need to enable it and configure it in your Minecraft server settings.

In the file server.properties
```properties
rcon.port=25575
enable-rcon=true
rcon.password=123
```


# Getting started


## Create connection

To create an RCON connection to a minecraft server, you need to create an object of the RCON class.

```php
use RMinks\RCON\RCON;

$Ip = '127.0.0.1'; //Server IP
$Port = 25575; //RCON port
$Password = '123'; //RCON password
$Timeout = 30; //Timeout in ms 

$RCON = new RCON($Ip, $Port, $Password, $Timeout);
```

## Send commands

To send a command to the server, use this method of the RСON class.
This method will return the response from the server.

```php
use RMinks\RCON\RCON;

...

$RCON->sendCommand('map de_dust2');//Example for CS:S
$RCON->sendCommand('time set day');//Example for Minecraft
```

## Server Responses

### All responses
To get all responses from the server use the following method

```php
use RMinks\RCON\RCON;

...

$Response = $RCON->ResponseService->getAllResponses();
```

### Last response
To get last response from the server use the following method

```php
use RMinks\RCON\RCON;

...

$Response = $RCON->ResponseService->getLastResponse();
```

### Response by id
You can get a specific server response from a list if you have the ID of that response.

```php
use RMinks\RCON\RCON;

...

$Response = $RCON->ResponseService->getResponse(3);
```

## Example

```php
namespace App\Http\Controllers;

use RMinks\RCON\RCON;

class RCONController extends Controller
{
    public function setDay()
    {
        $Ip = '127.0.0.1'; //Server IP
        $Port = 25575; //RCON port
        $Password = '123'; //RCON password
        $Timeout = 30; //Timeout in ms 

        $RCON = new RCON($Ip, $Port, $Password, $Timeout); //Create connection

        $RCON->sendCommand('time set day'); //Send command

        $Response = $RCON->ResponseService->getLastResponse(); //Get last response

        echo $Response;
    }
}
```

As a result of executing this code you should get the following result

```
    Set the time to 1000
```

## Example 2

```php
namespace App\Http\Controllers;

use RMinks\RCON\RCON;

class RCONController extends Controller
{
    public function changeMap()
    {
        $Ip = '192.168.1.39'; //Server IP
        $Port = 27015; //RCON port
        $Password = '12345'; //RCON password
        $Timeout = 30; //Timeout in ms 

        $RCON = new RCON($Ip, $Port, $Password, $Timeout); //Create connection

        $RCON->sendCommand('map de_dust2'); //Send command

        $Response = $RCON->ResponseService->getLastResponse(); //Get last response

        echo $Response;
        dd($Response);//For debug
    }
}
```

As a result of executing this code you should get the following result

```
    rcon from "x.x.x.x:xxxx": command "map de_dust2"

    L dd/mm/yyyy - hr:m:s: -------- Mapchange to de_dust2 --------
```
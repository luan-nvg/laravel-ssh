# Laravel SSH Tunnel
We had a similar challenge, specifically accessing a MySQL database over an SSH Tunnel and all of the Questions and Answers were helpful in finding a solution. However, we wanted something that would just plug and play with our Laravel applications and Lumen Services.

So we wrote this package. We hope you enjoy it!

## Requirements
This package has been tested against Laravel/Lumen versions 5.2. 5.3, and 5.4.

We do not support version <=5.1.

## Installation

```
composer require luan-nvg/laravel-ssh
```

### Register the Provider:

For Lumen services, add:

```php
$app->register(MNP\Tunneler\TunnelerServiceProvider::class);
```
to `bootstrap/app.php`. For Laravel applications, add:

```php
MNP\Tunneler\TunnelerServiceProvider::class,
```

to the `providers` array in `config/app.php`.

## Configuration
All configuration can and should be done in your `.env` file.
```ini

; Path to the nc executable
TUNNELER_NC_PATH=/usr/bin/nc
; Path to the bash executable
TUNNELER_BASH_PATH=/usr/bin/bash
; Path to the ssh executable
TUNNELER_SSH_PATH=/usr/bin/ssh
; Path to the nohup executable
TUNNELER_NOHUP_PATH=/usr/bin/nohup

; Log messages for troubleshooting
SSH_VERBOSITY=
NOHUP_LOG=/dev/null

; The identity file you want to use for ssh auth
TUNNELER_IDENTITY_FILE=/home/user/.ssh/id_rsa

; The local address and port for the tunnel
TUNNELER_LOCAL_PORT=13306
TUNNELER_LOCAL_ADDRESS=127.0.0.1

; The remote address and port for the tunnel
TUNNELER_BIND_PORT=3306
TUNNELER_BIND_ADDRESS=127.0.0.1

; The ssh connection: sshuser@sshhost:sshport
TUNNELER_USER=sshuser
TUNNELER_HOSTNAME=sshhost
TUNNELER_PORT=sshport

; How long to wait, in microseconds, before testing to see if the tunnel is created.
; Depending on your network speeds you will want to modify the default of .5 seconds
TUNNELER_CONN_WAIT=500000

; Do you want to ensure you have the Tunnel in place for each bootstrap of the framework?
TUNNELER_ON_BOOT=false

; Do you want to use additional SSH options when the tunnel is created?
TUNNELER_SSH_OPTIONS="-o StrictHostKeyChecking=no"
```

;Delay the compilation to allow time to process the tunneling so that it does not happen at the same time.
TIMEMOUT_TUNNEL=5

## Quickstart
The simplest way to use the Tunneler is to set `TUNNELER_ON_BOOT=true` in your `.env` file. This will ensure the tunnel is in place everytime the framework bootstraps.

However, there is minimal performance impact because the tunnel will get reused. You only have to bear the connection costs when the tunnel has been disconnected for some reason.

Then you can just configure your service, which we will demonstrate using a database connection. Add this under `'connections'` in your `config/database.php` file

```php
'mysql_tunnel' => [
    'driver'    => 'mysql',
    'host'      => env('TUNNELER_LOCAL_ADDRESS'),
    'port'      => env('TUNNELER_LOCAL_PORT'),
    'database'  => env('DB_DATABASE'),
    'username'  => env('DB_USERNAME'),
    'password'  => env('DB_PASSWORD'),
    'charset'   => env('DB_CHARSET', 'utf8'),
    'collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
    'prefix'    => env('DB_PREFIX', ''),
    'timezone'  => env('DB_TIMEZONE', '+00:00'),
    'strict'    => env('DB_STRICT_MODE', false),
],
```

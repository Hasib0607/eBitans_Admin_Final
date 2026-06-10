<?php

namespace App\Services\Visitors\LocationDrivers;

use Stevebauman\Location\Drivers\IpApi;

class FastIpApi extends IpApi
{
    const CURL_MAX_TIME = 1;
    const CURL_CONNECT_TIMEOUT = 1;
}

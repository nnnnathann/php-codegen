<?php

namespace Acme\MyApi;

use Acme\MyApi\Internal\ApiRequest;
use Acme\MyApi\Internal\ApiResponse;

interface TransportInterface
{
    public function sendHttpJson(ApiRequest $request) : ApiResponse;
}

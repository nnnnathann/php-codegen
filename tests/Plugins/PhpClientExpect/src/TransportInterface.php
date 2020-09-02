<?php

namespace Acme\MyApi;

interface TransportInterface
{
    public function sendHttpJson(ApiRequest $request) : ApiResponse;
}

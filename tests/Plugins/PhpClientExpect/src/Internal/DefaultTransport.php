<?php

namespace Acme\MyApi\Internal;

use Exception;
use RuntimeException;
use Acme\MyApi\TransportInterface;

final class DefaultTransport implements TransportInterface
{
    private array $options = [
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => [
            'Content-Type' => 'application/json',
        ],
    ];

    public function __construct($extraOptions)
    {
        $this->options = array_merge_recursive($this->options, $extraOptions);
    }

    public function sendHttpJson(ApiRequest $request) : ApiResponse
    {
        $req = curl_init();
        array_walk($this->options, fn ($value, $key) => curl_setopt($req, $key, $value));
        try {
            $output = curl_exec($req);
            $responseCode = curl_getinfo($req, CURLINFO_RESPONSE_CODE);
            if ($responseCode > 299) {
                return new ApiResponse(null, new RuntimeException('Unexpected response code from server: ' . $responseCode . ' ' . json_encode($request) . ' ' . $output));
            }

            return new ApiResponse($output);
        } catch (Exception $exception) {
            return new ApiResponse(null, new RuntimeException('error during service request', 0, $exception));
        }
    }
}

<?php

namespace Acme\MyApi;

use Acme\MyApi\Internal\ApiRequest;
use Acme\MyApi\Internal\ApiResponse;
use Acme\MyApi\Internal\DefaultTransport;
use Acme\MyApi\Data\CreatePostInput;
use Acme\MyApi\Data\CreatePostResult;
use Acme\MyApi\Data\PublishPostInput;
use Acme\MyApi\Data\PublishPostResult;
use TypeError;

final class Client
{
    private array $options = [
        'transport' => null,
        'curlopts' => [],
    ];
    private string $endpoint;

    public function __construct(string $endpoint, $options = [])
    {
        $this->endpoint = $endpoint;
        $this->options['transport'] = $options['transport'] ?: new DefaultTransport($options['curlopts'] ?? []);
    }

    /**
     * @param CreatePostInput $input
     * @return CreatePostResult
     * @throws ClientException
     */
    public function createPost(CreatePostInput $input) : CreatePostResult
    {
        return $this->run($input, CreatePostResult::class);
    }

    /**
     * @param PublishPostInput $input
     * @return PublishPostResult
     * @throws ClientException
     */
    public function publishPost(PublishPostInput $input) : PublishPostResult
    {
        return $this->run($input, PublishPostResult::class);
    }

    private function run($input, $resultClass)
    {
        try {
            $response = $this->options['transport']->sendHttpJson(new ApiRequest());

            return $this->hydrateResult($response->toArrayOrThrow(), new $resultClass);
        } catch (TypeError $error) {
            throw new ClientException('error decoding value', 500, $error);
        }
    }

    private function hydrateResult($data, $receiver)
    {
        foreach ($data as $key => $value) {
            $receiver[$key] = $value;
        }

        return $receiver;
    }
}
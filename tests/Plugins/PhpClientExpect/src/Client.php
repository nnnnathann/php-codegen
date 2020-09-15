<?php

namespace Acme\MyApi;

use Acme\MyApi\Internal\ApiRequest;
use Acme\MyApi\Internal\ApiResponse;
use Acme\MyApi\Internal\DefaultTransport;
use Acme\MyApi\Data\CreatePostInput;
use Acme\MyApi\Data\CreatePostResult;
use Acme\MyApi\Data\PublishPostInput;
use Acme\MyApi\Data\PublishPostResult;
use JsonException;
use TypeError;
use Exception;

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
        $this->options['transport'] = $options['transport'] ?? new DefaultTransport($options['curlopts'] ?? []);
    }

    /**
     * @param CreatePostInput $input
     * @return CreatePostResult
     * @throws ClientException
     */
    public function createPost(CreatePostInput $input) : CreatePostResult
    {
        return $this->run("createPost", $input, CreatePostResult::class);
    }

    /**
     * @param PublishPostInput $input
     * @return PublishPostResult
     * @throws ClientException
     */
    public function publishPost(PublishPostInput $input) : PublishPostResult
    {
        return $this->run("publishPost", $input, PublishPostResult::class);
    }

    private function run($commandName, $input, $resultClass)
    {
        try {
            $request = new ApiRequest($this->endpoint, $commandName, $input);
            $response = $this->options['transport']->sendHttpJson($request);

            return $resultClass::fromArray($response->toArrayOrThrow());
        } catch (TypeError $error) {
            throw new ClientException('error decoding value', 500, $error);
        } catch (JsonException $error) {
            throw new ClientException('error encoding value', 500, $error);
        } catch (Exception $error) {
            throw new ClientException('unspecified client error', 500, $error);
        }
    }
}
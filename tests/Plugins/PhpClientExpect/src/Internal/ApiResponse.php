<?php

namespace Acme\MyApi\Internal;

use RuntimeException;
use Acme\MyApi\ClientException;

class ApiResponse
{
    private ?string $data;
    private ?RuntimeException $exception;

    public function __construct(string $data = null, RuntimeException $exception = null)
    {
        $this->data = $data;
        $this->exception = $exception;
    }

    public function isError()
    {
        return $this->exception !== null;
    }

    public function isOk()
    {
        return !$this->isError();
    }

    public function toArrayOrThrow()
    {
        if ($this->isError()) {
            throw $this->exception;
        }
        return $this->toArray();
    }

    public function toArray()
    {
        if ($this->isError()) {
            return ['error' => $this->exception->getMessage()];
        }

        return json_decode($this->data, true);
    }
}

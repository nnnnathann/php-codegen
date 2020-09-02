<?php

namespace CodeGen\Plugins\PhpClient;

final class PhpClientOptions
{
    public string $packageName;
    public string $packageNamespace;

    public function __construct(string $packageName, string $packageNamespace)
    {
        $this->packageName = $packageName;
        $this->packageNamespace = $packageNamespace;
    }
}

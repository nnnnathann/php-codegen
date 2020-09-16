<?php

namespace CodeGen;

use CodeGen\Data\ServiceDefinition;

interface GeneratorPluginInterface
{
    public function defaultDirectory() : string;
    public function output(string $directory, ServiceDefinition $service);
}

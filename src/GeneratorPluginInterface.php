<?php

namespace CodeGen;

use CodeGen\Data\ServiceDefinition;

interface GeneratorPluginInterface
{
    public function output(string $directory, ServiceDefinition $service);
}

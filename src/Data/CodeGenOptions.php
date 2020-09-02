<?php

namespace CodeGen\Data;

use CodeGen\GeneratorPluginInterface;

final class CodeGenOptions
{
    /** @var GeneratorPluginInterface[] string keyed map of output plugins*/
    public array $plugins;

    public function __construct(array $plugins)
    {
        $this->plugins = $plugins;
    }
}

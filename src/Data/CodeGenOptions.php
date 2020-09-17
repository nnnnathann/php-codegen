<?php

namespace CodeGen\Data;

use CodeGen\File\DiskIO;
use CodeGen\File\FileIO;
use CodeGen\GeneratorPluginInterface;

final class CodeGenOptions
{
    /** @var GeneratorPluginInterface[] string keyed map of output plugins*/
    public array $plugins;

    public FileIO $files;

    public function __construct(array $plugins, FileIO $files =  null)
    {
        $this->plugins = $plugins;
        $this->files = $files ?? new DiskIO();
    }
}

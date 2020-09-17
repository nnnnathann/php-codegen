<?php

namespace CodeGen;

use CodeGen\Data\CodeGenOptions;
use CodeGen\Data\ServiceDefinition;
use CodeGen\Reflection\TypeReflector;

final class CodeGen
{
    private CodeGenOptions $options;
    private TypeReflector  $reflector;

    public function __construct(CodeGenOptions $options)
    {
        $this->options = $options;
        $this->reflector = new TypeReflector();
    }

    public function generate(string $serviceInterfaceClass, string $outputDir)
    {
        $def = $this->reflect($serviceInterfaceClass);
        foreach ($this->options->plugins as $dir => $plugin) {
            if (is_numeric($dir)) {
                $dir = $plugin->defaultDirectory();
            }
            $pluginOutputDir = implode('/', [rtrim($outputDir, '/'), ltrim($dir, '/')]);
            $this->options->files->mkdirp($pluginOutputDir);
            $plugin->output($pluginOutputDir, $def);
        }
    }

    private function reflect(string $serviceInterface) : ServiceDefinition
    {
        return $this->reflector->getServiceDefinition($serviceInterface);
    }
}

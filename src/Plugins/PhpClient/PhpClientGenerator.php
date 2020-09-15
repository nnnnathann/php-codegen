<?php

namespace CodeGen\Plugins\PhpClient;

use CodeGen\Data\Action;
use CodeGen\Data\ServiceDefinition;
use CodeGen\Data\Types\ArrayType;
use CodeGen\Data\Types\NumberType;
use CodeGen\Data\Types\ObjectType;
use CodeGen\Data\Types\PrimitiveType;
use CodeGen\Data\TypeValue;
use CodeGen\File\DiskIO;
use CodeGen\File\FileIO;
use CodeGen\File\FileWriterInterface;
use CodeGen\File\PhpTemplatedDirectory;
use CodeGen\GeneratorPluginInterface;
use CodeGen\Plugins\Common\PhpDataTypes;
use RuntimeException;

final class PhpClientGenerator implements GeneratorPluginInterface
{
    private PhpClientOptions $options;
    private FileIO $disk;

    public function __construct(PhpClientOptions $options, FileIO $io=null)
    {
        $this->options = $options;
        $this->disk = $io ?? new DiskIO();
    }

    public function defaultDirectory(): string
    {
        return 'php-client';
    }

    public function output(string $directory, ServiceDefinition $service)
    {
        $templateGen = new PhpTemplatedDirectory(__DIR__ . '/Templates/*.php');
        $templateGen->output($directory . '/src', array_merge($service->getTemplateData(), [
            'namespace' => $this->options->packageNamespace,
        ]));
        $dataTypes = new PhpDataTypes($this->disk);
        $outputDir = $directory . '/src/Data';
        $dataTypes->generateActionDataTypes($service->actions, $outputDir, $this->options->packageNamespace . '\\Data');
        $this->disk->mkdirAndWrite($directory . '/composer.json', json_encode($this->composerDef($service), JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES));
    }

    private function composerDef(ServiceDefinition $service)
    {
        return [
            'name' => $this->options->packageName,
            'require' => [
                'psr/http-message' => '*',
                'ext-curl' => '*',
            ],
            'autoload' => [
                'psr-4' => [
                    $this->options->packageNamespace . '\\' => 'src/',
                ],
            ],
        ];
    }
}

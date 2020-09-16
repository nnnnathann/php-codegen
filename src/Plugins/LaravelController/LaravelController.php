<?php


namespace CodeGen\Plugins\LaravelController;


use CodeGen\Data\ServiceDefinition;
use CodeGen\File\DiskIO;
use CodeGen\File\FileIO;
use CodeGen\File\PhpTemplatedDirectory;
use CodeGen\GeneratorPluginInterface;
use CodeGen\Plugins\Common\PhpDataTypes;

final class LaravelController implements GeneratorPluginInterface
{
    private LaravelControllerOptions $options;
    private FileIO $disk;

    public function __construct(LaravelControllerOptions $options, FileIO $io=null)
    {
        $this->options = $options;
        $this->disk = $io ?? new DiskIO();
    }

    public function defaultDirectory(): string
    {
        return 'php-laravel';
    }

    public function output(string $directory, ServiceDefinition $service)
    {
        $templateGen = new PhpTemplatedDirectory(__DIR__ . '/Templates/*.php');
        $types = new PhpDataTypes($this->disk);
        $types->generateActionDataTypes($service->actions, $directory . '/Data', 'CodeGen\\Laravel\\Data');
        $templateGen->output($directory, $service->getTemplateData());
    }
}
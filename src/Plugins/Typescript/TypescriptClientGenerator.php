<?php


namespace CodeGen\Plugins\Typescript;


use CodeGen\Data\Action;
use CodeGen\Data\ServiceDefinition;
use CodeGen\File\FileWriterInterface;
use CodeGen\GeneratorPluginInterface;

final class TypescriptClientGenerator implements GeneratorPluginInterface
{
    private string $fileName;
    private string $className;

    public function __construct(string $fileName, string $className)
    {
        $this->fileName = $fileName;
        $this->className = $className;
    }

    public function output(string $directory, ServiceDefinition $service)
    {
        $types = array_reduce($service->actions, function (Action $action) {
            $ucName = ucfirst($action->name);
            return <<<TYPES
interface {$ucName}Input {} 
type {$ucName}Result = Result<{}>
    public {$action->name}({$ucName}Input input) : Promise<{$ucName}Result>
TYPES;
        });
        $methods = array_reduce($service->actions, function (Action $action) {
            $ucName = ucfirst($action->name);
            return <<<METHOD
    public {$action->name}({$ucName}Input input) : Promise<{$ucName}Result>
METHOD;
        });
        $content = <<<CLIENT
type Result<T> = Ok<T> | Err;
interface Ok<T> { tag: "ok", data: T }
interface Err<T> { tag: "error", message: string }
type Fetch = (input: RequestInfo, options?: RequestInit) : Promise<Response>
class $className {
    public constructor(fetch : Fetch) {
        this.fetch = window.fetch;
    }
    $methods
}
CLIENT;
        file_put_contents($directory . '/' . $this->fileName, $content);
    }

    public function defaultDirectory(): string
    {
        return 'ts-client';
    }
}
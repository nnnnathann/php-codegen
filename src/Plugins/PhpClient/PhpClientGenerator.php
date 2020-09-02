<?php

namespace CodeGen\Plugins\PhpClient;

use CodeGen\Data\Action;
use CodeGen\Data\ServiceDefinition;
use CodeGen\Data\Types\ArrayType;
use CodeGen\Data\Types\NumberType;
use CodeGen\Data\Types\ObjectType;
use CodeGen\Data\Types\PrimitiveType;
use CodeGen\Data\TypeValue;
use CodeGen\File\PhpTemplatedDirectory;
use CodeGen\GeneratorPluginInterface;

final class PhpClientGenerator implements GeneratorPluginInterface
{
    private PhpClientOptions $options;

    public function __construct(PhpClientOptions $options)
    {
        $this->options = $options;
    }

    public function output(string $directory, ServiceDefinition $service)
    {
        $templateGen = new PhpTemplatedDirectory(__DIR__ . '/Templates/*.php');
        $templateGen->output($directory . '/src', array_merge($service->getTemplateData(), [
            'namespace' => $this->options->packageNamespace,
        ]));
        array_walk($service->actions, fn ($action) => $this->generateDataTypes($action, $directory . '/src/Data'));
        $this->writeFile($directory . '/composer.json', json_encode($this->composerDef($service), JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES));
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

    private function generateDataTypes(Action $action, string $outputDirectory)
    {
        if ($action->input instanceof ObjectType) {
            $this->writeObjectAsStruct($action->input, $outputDirectory . '/' . ucfirst($action->name) . 'Input.php');
        }
        if ($action->result instanceof ObjectType) {
            $this->writeObjectAsStruct($action->result, $outputDirectory . '/' . ucfirst($action->name) . 'Result.php');
        }
    }

    private function writeObjectAsStruct(ObjectType $input, string $outputFile)
    {
        $className = pathinfo($outputFile, PATHINFO_FILENAME);
        $props = [];
        $consDoc = [];
        $consArgs = [];
        $consBody = [];
        foreach ($input->properties as $propName => $type) {
            $props[] = sprintf("    /** @var {$this->phpTypeString($type)} */\n    public \${$propName};");
            $consDoc[] = sprintf("     * @param {$this->phpTypeString($type)} \${$propName}");
            $consArgs[] = sprintf("\$%s", $propName);
            $consBody[] = sprintf("        \$this->%s = \$%s;", $propName, $propName, $propName);
        }
        $props = implode("\n", $props);
        $consDoc = "    /**\n" . implode("\n", $consDoc) . "\n     */";
        $consArgs = implode(", ", $consArgs);
        $consBody = implode("\n", $consBody);
        $structClass = <<<STRUCT
<?php

namespace {$this->options->packageNamespace}\Data;


final class $className
{
$props

$consDoc
    public function __construct($consArgs)
    {
$consBody
    }
}
STRUCT;
        $this->writeFile($outputFile, $structClass);
    }

    private function phpTypeString(TypeValue $type)
    {
        if ($type instanceof ArrayType) {
            return sprintf("%s[]", $this->phpTypeString($type->itemType));
        }
        if ($type instanceof NumberType) {
            return 'int|float';
        }
        if ($type instanceof PrimitiveType) {
            return $type->typeAsString;
        }
        return "mixed";
    }

    private function writeFile($fileName, $content)
    {
        $dir = dirname($fileName);
        if (!file_exists($dir)) {
            mkdir($dir, true);
        }
        file_put_contents($fileName, $content);
    }
}

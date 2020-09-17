<?php


namespace CodeGen\Plugins\Common;


use CodeGen\Data\Action;
use CodeGen\Data\Types\ArrayType;
use CodeGen\Data\Types\ObjectType;
use CodeGen\Data\Types\PrimitiveType;
use CodeGen\Data\TypeValue;
use CodeGen\File\FileIO;

final class PhpDataTypes
{
    private FileIO $disk;

    public function __construct(FileIO $disk)
    {
        $this->disk = $disk;
    }

    /**
     * @param Action[] $actions
     * @param string $outputDirectory
     * @param string $packageNamespace
     */
    public function generateActionDataTypes(array $actions, string $outputDirectory, string $packageNamespace)
    {
        array_walk($actions, fn ($action) => $this->generateDataTypes($action, $outputDirectory, $packageNamespace));
    }

    public function generateDataTypes(Action $action, string $outputDirectory, string $packageNamespace)
    {
        if ($action->input instanceof ObjectType) {
            $this->writeObjectAsStruct($action->input, $outputDirectory . '/' . ucfirst($action->name) . 'Input.php', $packageNamespace);
        }
        if ($action->result instanceof ObjectType) {
            $this->writeObjectAsStruct($action->result, $outputDirectory . '/' . ucfirst($action->name) . 'Result.php', $packageNamespace);
        }
    }

    private function writeObjectAsStruct(ObjectType $input, string $outputFile, string $packageNamespace)
    {
        $className = pathinfo($outputFile, PATHINFO_FILENAME);
        $props = [];
        $consDoc = [];
        $consArgs = [];
        $consBody = [];
        $fromArrayArgs = [];
        foreach ($input->properties as $propName => $type) {
            $typeString = $this->phpTypeString($type);
            if ($type instanceof ObjectType) {
                $ucPropName = ucfirst($propName);
                $propStructClassName = "{$className}{$ucPropName}";
                $this->writeObjectAsStruct($type, dirname($outputFile) . "/{$className}{$ucPropName}.php", $packageNamespace);
                $typeString = $propStructClassName;
            }
            $props[] = sprintf("    /** @var {$typeString} */\n    public \${$propName};");
            $consDoc[] = sprintf("     * @param {$typeString} \${$propName}");
            $consArgs[] = sprintf("\$%s", $propName);
            $consBody[] = sprintf("        \$this->%s = \$%s;", $propName, $propName);
            $fromArrayArgs[] = sprintf("\$data['%s']", $propName);
        }
        $props = implode("\n", $props);
        $consDoc = "    /**\n" . implode("\n", $consDoc) . "\n     */";
        $consArgs = implode(", ", $consArgs);
        $consBody = implode("\n", $consBody);
        $fromArrayArgs = implode(",\n            ", $fromArrayArgs);
        $structClass = <<<STRUCT
<?php

namespace {$packageNamespace};


class $className
{
$props

$consDoc
    public function __construct($consArgs)
    {
$consBody
    }

    public static function fromArray(array \$data) : self
    {
        return new self(
            $fromArrayArgs
        );
    }
}
STRUCT;
        $this->disk->mkdirAndWrite($outputFile, $structClass);
    }

    private function phpTypeString(TypeValue $type)
    {
        if ($type instanceof ArrayType) {
            return sprintf("%s[]", $this->phpTypeString($type->itemType));
        }
        return $type->typeAsString;
    }
}
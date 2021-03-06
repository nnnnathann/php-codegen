<?php

namespace Test\Plugins;

use CodeGen\Data\ServiceDefinition;
use CodeGen\File\FileSet;
use CodeGen\GeneratorPluginInterface;
use PHPUnit\Framework\TestCase;
use Test\MyApi\Expectations;

abstract class AbstractPluginTestCase extends TestCase
{
    protected string $expectedOutputDir;

    private ServiceDefinition $definition;

    /** @var string[] */
    private $dirs;

    public function setUp() : void
    {
        $this->definition = (new Expectations())->getExpectedDefinition();
    }

    public function tearDown() : void
    {
        if (!isset($_ENV['NO_CLEANUP'])) {
            array_walk($this->dirs, [$this, 'removeDirectory']);
        }
    }

    public function validateOutput(GeneratorPluginInterface $plugin, $expectedOutputDir)
    {
        $outputDir = $this->writeOutput($plugin, $expectedOutputDir);
        $expectedOutput = FileSet::fromGlob($expectedOutputDir . '/*', $expectedOutputDir);
        $actualOutput = FileSet::fromGlob($outputDir . '/*', $outputDir);
        $expectedOutput->diff($actualOutput, function ($a, $b, $relPath) {
            $this->assertEquals($a, $b, $relPath);

            return $a === $b;
        });
    }

    public function writeOutput(GeneratorPluginInterface $plugin, $expectedOutputDir)
    {
        $tmpDir = __DIR__ . '/../tmp';
        $outputDir = implode('/', [$tmpDir, basename($expectedOutputDir)]);
        if (file_exists($outputDir)) {
            $this->removeDirectory($outputDir);
        }
        mkdir($outputDir, 0777, true);
        $this->dirs[] = $outputDir;
        $plugin->output($outputDir, $this->definition);
        return $outputDir;
    }

    public function removeDirectory($path)
    {
        if (!file_exists($path)) {
            return;
        }
        $files = glob($path . '/*');
        foreach ($files as $file) {
            is_dir($file) ? $this->removeDirectory($file) : unlink($file);
        }
        rmdir($path);
    }

    public function copyr($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->copyr($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}

<?php

namespace CodeGen\File;

use RuntimeException;

final class PhpTemplatedDirectory
{
    private string $sourceDir;

    public function __construct(string $sourceDir)
    {
        $this->sourceDir = $sourceDir;
    }

    public function output(string $directory, array $templateData)
    {
        $inputFiles = $this->glob($this->sourceDir);
        array_walk($inputFiles, fn ($inputFile) => $this->captureAndWrite($templateData, $inputFile, $this->outputOf($inputFile, $directory)));
    }

    private function glob($pattern)
    {
        $files = glob($pattern);
        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, $this->glob($dir . '/' . basename($pattern)));
        }

        return $files;
    }

    private function outputOf(string $inputFile, string $outputDir)
    {
        return preg_replace('/^' . preg_quote(dirname($this->sourceDir), '/') . '/', $outputDir, $inputFile);
    }

    public function captureAndWrite(array $templateData, $inputFile, string $outputFile)
    {
        extract($templateData, EXTR_OVERWRITE);
        ob_start();
        include $inputFile;
        if (!file_exists(dirname($outputFile))) {
            if (!mkdir($concurrentDirectory = dirname($outputFile), 0777) && !is_dir($concurrentDirectory)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }
        file_put_contents($outputFile, ob_get_clean());
    }
}

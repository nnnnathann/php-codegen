<?php

namespace CodeGen\File;

use Exception;
use RuntimeException;

final class PhpTemplatedDirectory
{
    private string $sourceDir;
    private FileWriterInterface $writer;

    public function __construct(string $sourceDir, FileWriterInterface $writer=null)
    {
        $this->writer = $writer ?? new DiskIO();
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
        try {
            extract($templateData, EXTR_OVERWRITE);
            ob_start();
            include $inputFile;
            $this->writer->mkdirAndWrite($outputFile, ob_get_clean());
        } catch (Exception $exception) {
            throw new RuntimeException('unable to write ' . $inputFile . ' to ' . $outputFile, 0, $exception);
        }
    }
}

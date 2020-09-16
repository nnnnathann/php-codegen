<?php

namespace CodeGen\File;

use RuntimeException;

final class FileSet
{
    /** @var string[] */
    private array $files;
    private string $baseDir;
    /** @var FileIO */
    private FileIO $disk;

    public function __construct(array $files, string $baseDir, FileIO $disk)
    {
        $this->disk = $disk;
        $this->files = $files;
        $this->baseDir = $baseDir;
    }

    public function write(string $relativePath, string $content)
    {
        $filepath = $this->join($this->baseDir, $relativePath);
        file_put_contents($filepath, $content);
        $this->files[] = realpath($filepath);
    }

    public function diff(self $fileSet, callable $contentCompare)
    {
        return array_reduce($this->files, function ($carry, $myFile) use ($fileSet, $contentCompare) {
            $relPath = $this->relPath($myFile);
            $myFileContent = $this->readRelative($relPath);
            $theirFileContent = $fileSet->readRelative($relPath);

            return array_merge($carry, [$relPath => $contentCompare($myFileContent, $theirFileContent, $relPath)]);
        }, []);
    }

    public function readRelative($relativePath)
    {
        $file = array_filter($this->files, fn ($file) => $this->relPath($file) === $relativePath);
        if (empty($file)) {
            throw new RuntimeException('file ' . $relativePath . ' not found');
        }

        return file_get_contents(array_values($file)[0]);
    }

    public static function fromGlob(string $pattern, string $baseDir)
    {
        return new self(self::glob($pattern), $baseDir, new DiskIO());
    }

    private static function glob($pattern)
    {
        $files = glob($pattern);
        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, self::glob($dir . '/' . basename($pattern)));
        }

        return $files;
    }

    private function relPath(string $file)
    {
        return preg_replace('/^' . preg_quote($this->baseDir, '/') . '/', '', $file);
    }

    private function join($left, $right)
    {
        return rtrim($left, '/') . '/' . ltrim($right, '/');
    }
}

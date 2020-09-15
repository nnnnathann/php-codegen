<?php


namespace CodeGen\File;


use RuntimeException;

final class DiskIO implements FileIO
{
    public function mkdirAndWrite(string $filename, string $content)
    {
        self::mkdirp(dirname($filename));
        file_put_contents($filename, $content);
    }

    public function mkdirp(string $dir)
    {
        if (!file_exists($dir)) {
            if (!mkdir($concurrentDirectory = $dir, 0777, true) && !is_dir($concurrentDirectory)) {
                throw new RuntimeException(sprintf('error creating "%s"', $concurrentDirectory));
            }
        }
    }

    public function exists(string $filename): bool
    {
        return file_exists($filename);
    }

    public function read(string $filename): string
    {
        return file_get_contents($filename);
    }
}
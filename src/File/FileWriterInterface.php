<?php

namespace CodeGen\File;

interface FileWriterInterface
{
    public function mkdirAndWrite(string $filename, string $content);

    public function mkdirp(string $dir);
}
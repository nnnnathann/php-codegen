<?php


namespace CodeGen\File;


interface FileReaderInterface
{
    public function exists(string $filename) : bool;
    public function read(string $filename) : string;
}
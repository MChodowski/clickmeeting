<?php

namespace App\Application;

interface FileManager
{
    public function save(string $filePath, string $destinationFilePath): bool;
}
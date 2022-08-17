<?php

namespace App\Application;

interface ImageOptimizer
{
    public function resize(string $filename, int $width, int $height);
}
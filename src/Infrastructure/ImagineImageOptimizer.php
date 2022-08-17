<?php

namespace App\Infrastructure;

use App\Application\ImageOptimizer;
use Imagine\Gd\Imagine;

class ImagineImageOptimizer implements ImageOptimizer
{
    private $imagine;

    public function __construct()
    {
        $this->imagine = new Imagine();
    }

    public function resize(string $filename, int $width, int $height): void
    {
        $imageInfo = getimagesize($filename);
        dump($imageInfo);
//        $ratio = $iwidth / $iheight;
//        $width = self::MAX_WIDTH;
//        $height = self::MAX_HEIGHT;
//        if ($width / $height > $ratio) {
//            $width = $height * $ratio;
//        } else {
//            $height = $width / $ratio;
//        }
//
//        $photo = $this->imagine->open($filename);
//        $photo->resize(new Box($width, $height))->save($filename);
    }
}
<?php

namespace App\Infrastructure;

use App\Application\ImageOptimizer;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class ImagineImageOptimizer implements ImageOptimizer
{
    private $imagine;

    private string $resizedImagesDirectory;

    public function __construct(string $resizedImagesDirectory)
    {
        $this->imagine = new Imagine();
        $this->resizedImagesDirectory = $resizedImagesDirectory;
    }

    public function resize(string $filePath, int $width, int $height): string
    {
        $imageInfo = getimagesize($filePath);
        if (empty($imageInfo)) {
            throw new \InvalidArgumentException('Niepoprawne zdjęcie');
        }

        if ($width <= 0 || $height <= 0) {
            throw new \InvalidArgumentException('Wysokość i szerokość zdjęcia nie mogą mieć wartości ujemnej lub 0');
        }

        $ratio = $imageInfo[0] / $imageInfo[1];
        if ($width / $height > $ratio) {
            $width = $height * $ratio;
        } else {
            $height = $width / $ratio;
        }

        $photo = $this->imagine->open($filePath);
        $resizedFilePath = $this->resizedImagesDirectory.basename($filePath);
        $photo->resize(new Box($width, $height))->save($resizedFilePath);

        return $resizedFilePath;
    }
}
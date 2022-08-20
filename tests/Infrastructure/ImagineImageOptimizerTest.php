<?php

namespace App\Tests\Infrastructure;

use App\Infrastructure\ImagineImageOptimizer;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;

class ImagineImageOptimizerTest extends KernelTestCase
{
    private ContainerInterface $container;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->container = static::getContainer();
    }

    public function testResizeWithWrongFileType()
    {
        /** @var ImagineImageOptimizer $imagineOptimizer */
        $imagineOptimizer = $this->container->get(ImagineImageOptimizer::class);

        $fileName = 'example-pdf.pdf';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Niepoprawne zdjęcie');

        $imagineOptimizer->resize($_ENV['FILES_DIRECTORY'].$fileName, 150, 150);
    }

    public function testResize()
    {
        /** @var ImagineImageOptimizer $imagineOptimizer */
        $imagineOptimizer = $this->container->get(ImagineImageOptimizer::class);

        $fileName = 'example.jpg';

        $resizedImage = $imagineOptimizer->resize($_ENV['FILES_DIRECTORY'].$fileName, 150, 150);

        /** @var Filesystem $filesystem */
        $filesystem = $this->container->get(Filesystem::class);

        $this->assertTrue($filesystem->exists($resizedImage));
    }

    public function testResizeWithInvalidWidthHeight()
    {
        /** @var ImagineImageOptimizer $imagineOptimizer */
        $imagineOptimizer = $this->container->get(ImagineImageOptimizer::class);

        $fileName = 'example.jpg';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Wysokość i szerokość zdjęcia nie mogą mieć wartości ujemnej lub 0');
        $imagineOptimizer->resize($_ENV['FILES_DIRECTORY'].$fileName, 0, 0);
    }
}

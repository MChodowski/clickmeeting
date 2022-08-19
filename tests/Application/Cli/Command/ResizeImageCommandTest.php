<?php

namespace App\Tests\Application\Cli\Command;

use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class ResizeImageCommandTest extends KernelTestCase
{
    public function testCommandWithoutFileNameOption()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:resize-image');
        $commandTester = new CommandTester($command);

        $commandTester->execute([]);
        Assert::assertEquals($commandTester->getStatusCode(), Command::FAILURE);

        $this->assertStringContainsString(
            'Wystąpił błąd podczas wykonywania commanda ResizeImageCommand: Brak podanej ścieżki pliku',
            $commandTester->getDisplay()
        );
    }
}

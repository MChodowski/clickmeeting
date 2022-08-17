<?php

namespace App\Cli\Command;

use App\Application\ImageOptimizer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand(
    name: 'app:resize-image',
    description: 'Resizes images',
    hidden: false
)]
class ResizeImageCommand extends Command
{
    private Filesystem $filesystem;

    private ImageOptimizer $imageOptimizer;

    const ROOT_DIRECTORY = 'var/files';

    public function __construct(Filesystem $filesystem, ImageOptimizer $imageOptimizer,string $name = null)
    {
        parent::__construct($name);
        $this->filesystem = $filesystem;
        $this->imageOptimizer = $imageOptimizer;
    }

    protected function configure(): void
    {
        $this
            ->addOption('fileName', 'fileName', InputOption::VALUE_REQUIRED, 'File name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fileName = $input->getOption('fileName');
        if ($fileName === null) {
            throw new \InvalidArgumentException('Brak podanej ścieżki pliku');
        }

        $filePath = self::ROOT_DIRECTORY.$fileName;
        if (!$this->filesystem->exists($filePath)) {
            throw new \InvalidArgumentException('Plik nie istnieje');
        }
        $this->imageOptimizer->resize($filePath, 150, 150);

        return Command::SUCCESS;
    }
}
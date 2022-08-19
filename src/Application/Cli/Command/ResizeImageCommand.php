<?php

namespace App\Application\Cli\Command;

use App\Application\FileManager;
use App\Application\ImageOptimizer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
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

    private FileManager $fileManager;

    private string $filesDirectory;

    const INPUT_FILENAME = 'fileName';

    public function __construct(
        Filesystem $filesystem,
        ImageOptimizer $imageOptimizer,
        FileManager $fileManager,
        string $filesDirectory,
        string $name = null
    ) {
        parent::__construct($name);
        $this->filesystem = $filesystem;
        $this->imageOptimizer = $imageOptimizer;
        $this->fileManager = $fileManager;
        $this->filesDirectory = $filesDirectory;
    }

    protected function configure(): void
    {
        $this
            ->addOption(self::INPUT_FILENAME, self::INPUT_FILENAME, InputOption::VALUE_REQUIRED, 'File name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $fileName = $input->getOption(self::INPUT_FILENAME);
            if ($fileName === null) {
                throw new \InvalidArgumentException('Brak podanej ścieżki pliku');
            }

            $filePath = $this->filesDirectory.$fileName;

            if (!$this->filesystem->exists($filePath)) {
                throw new \InvalidArgumentException('Plik nie istnieje');
            }

            $resizedFilePath = $this->imageOptimizer->resize($filePath, 150, 150);

            $result = $this->fileManager->save($resizedFilePath, '/'.basename($resizedFilePath));

            $output->writeln($result ? "Plik pomyślnie zapisany" : "Nie udało się zapisać pliku");
        } catch (\Throwable $exception) {
            //TODO logowanie błędów
            $output->writeln(
                'Wystąpił błąd podczas wykonywania commanda ResizeImageCommand: '.$exception->getMessage()
            );

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
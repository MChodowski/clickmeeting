<?php

namespace App\Infrastructure;

use App\Application\FileManager;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DropboxFileManager implements FileManager
{
    private Filesystem $filesystem;

    private string $dropboxUploadEndpoint;

    private string $dropboxToken;

    public function __construct(Filesystem $filesystem, string $dropboxUploadEndpoint, string $dropboxToken)
    {
        $this->filesystem = $filesystem;
        $this->dropboxUploadEndpoint = $dropboxUploadEndpoint;
        $this->dropboxToken = $dropboxToken;
    }

    public function save(string $filePath, string $destinationFilePath): bool
    {
        $this->validateClientPrerequisites();

        $curlHandle = curl_init($this->dropboxUploadEndpoint);

        $headers = [];
        $headers[] = 'Authorization: Bearer '.$this->dropboxToken;
        $headers[] = 'Content-Type: application/octet-stream';
        $dropboxApiArg = [
            'autorename' => false,
            'mode' => 'add',
            'mute' => false,
            'path' => $destinationFilePath,
            'strict_conflict' => false
        ];
        $headers[] = 'Dropbox-API-Arg: '.json_encode($dropboxApiArg);

        if (!$this->filesystem->exists($filePath)) {
            throw new FileNotFoundException('Plik nie istnieje: '. $filePath);
        }

        curl_setopt_array($curlHandle, array(
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => file_get_contents($filePath),
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_RETURNTRANSFER  => true,
            )
        );
        $response = curl_exec($curlHandle);
        $info = curl_getinfo($curlHandle);

        if ($info['http_code'] >= 200 && $info['http_code'] < 300) {
            return true;
        } else {
            throw new HttpException(
                (int) $info['http_code'],
                sprintf('Wystąpił problem przy wysyłaniu pliku do Dropbox: %s', $response),
            );
        }
    }

    private function validateClientPrerequisites(): void
    {
        if (empty($this->dropboxUploadEndpoint)) {
            throw new \Exception('Brak zdefiniowanego endpointu');
        }

        if (empty($this->dropboxToken)) {
            throw new \Exception('Brak danych uwierzytelniających');
        }
    }
}
<?php


namespace App\Service;


use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private string $targetDirectory;

    private SluggerInterface $slugger;

    private Filesystem $filesystem;

    public function __construct(string $targetDirectory, SluggerInterface $slugger, Filesystem $filesystem)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
        $this->filesystem = $filesystem;
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move(
                $this->getTargetDirectory(),
                $newFilename
            );
        } catch (FileException|\Exception $exception) {
            echo $exception->getMessage();
        }

        return $newFilename;
    }

    public function remove(string $file): void
    {
        $this->filesystem->remove($this->getTargetDirectory() . $file);
    }
    
    private function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
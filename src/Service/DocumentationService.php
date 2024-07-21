<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class DocumentationService
{
    private $filesystem;
    private $finder;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
        $this->finder = new Finder();
    }

    public function getAllDocumentation(): array
    {
        $docs = [];
        $this->finder->files()->in(__DIR__ . '/../../templates/doc');

        foreach ($this->finder as $file) {
            $docs[] = [
                'path' => $file->getRelativePathname(),
                'content' => $file->getContents()
            ];
        }

        return $docs;
    }
}


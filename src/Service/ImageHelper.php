<?php
namespace App\Service;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;


use Imagick;

class ImageHelper
{
    private EntityManagerInterface $entityManager;

    private SluggerInterface $slugger;




    public function __construct(EntityManagerInterface $entityManager,SluggerInterface $slugger)
    {
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;

    }




    public function saveImage($imageFile, $fileInfo, $imageDirectory, $isTest = false): bool
    {
        if (!file_exists($imageFile->getPathname())) {

            return false;
        }


        if ($isTest) {
            // Si c'est un fichier de test, on copie l'image originale
            if (!copy($imageFile->getPathname(), $imageDirectory . $fileInfo['newPath'])) {
                return false;
            }
        } else {
            // Sinon, on déplace l'image
            $this->moveImage($imageFile, $fileInfo['newPath'],$imageDirectory);
        }

        $this->createThumbnail($imageDirectory . $fileInfo['newPath'], $imageDirectory .  $fileInfo['thumbPath']);
        return true;
    }



    public function prepareFileInfo($file): array
    {
        $extension = $file->guessExtension();
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid();
        $newPath =  '/' . $newFilename . '.' . $extension;
        $thumbPath =  '/' . $newFilename . '-thumb.' . $extension;

        return [
            'extension' => $extension,
            'filename' => $newFilename,
            'newPath' => $newPath,
            'thumbPath' => $thumbPath,
        ];
    }
    private function moveImage($file,  $newPath,$imagesDirectory)
    {
        try {
            $file->move($imagesDirectory, $newPath);
        } catch (FileException $e) {
            throw new \Exception("Échec du déplacement de l'image : " . $e->getMessage());
        }
    }



    private function createThumbnail(string $sourcePath, string $thumbPath)
    {

        try {
            $image = new \Imagick($sourcePath);
            $image->thumbnailImage(100, 0);
            $image->writeImage($thumbPath);
            $image->clear();
            $image->destroy();
        } catch (\Exception $e) {
            throw new \Exception("Échec de la création de la thumb : " . $e->getMessage());
        }
    }


    public function deleteImages($deleteImagesIds,$imagesDirectory): void
    {
        if (!empty($deleteImagesIds)) {
            foreach ($deleteImagesIds as $imageId) {
                $this->deleteImage($imageId,$imagesDirectory);
            }
        }
    }

    private function deleteImage($imageId,$imagesDirectory): void
    {

        $image = $this->entityManager->getRepository(Image::class)->find($imageId);
        if ($image) {
            $this->entityManager->remove($image);
            $this->entityManager->flush();

            $filesToRemove = [];
            $filesToRemove[] = $imagesDirectory . '/' . $image->getFilenameWithExtension();
            $filesToRemove[] = $imagesDirectory . '/' . $image->getFilenameWithExtension('thumb');
            foreach ($filesToRemove as $filePath) {
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }else{
            throw new \Exception("Image non trouvée: " . $imageId);
        }
    }
}

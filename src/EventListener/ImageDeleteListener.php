<?php
namespace App\EventListener;

use Doctrine\ORM\Event\PostRemoveEventArgs;
use App\Entity\Image;

class ImageDeleteListener
{

    private $imagesDirectory;

    public function __construct(string $imagesDirectory)
    {
        $this->imagesDirectory = $imagesDirectory;
    }

    public function postRemove(Image $image, PostRemoveEventArgs $args)
    {
       /* todo : modifier les repertoire pour les images pour eviter le 's' en trop dans le repertoire 'animals' et 'habitats' */



        $filesToRemove = [];
        $filesToRemove[] = $this->imagesDirectory . '/' . $image->getEntityType().'s/'. $image->getFilenameWithExtension();
        $filesToRemove[] = $this->imagesDirectory . '/' . $image->getEntityType().'s/' . $image->getFilenameWithExtension('thumb');
        foreach ($filesToRemove as $filePath) {
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }


    }
}

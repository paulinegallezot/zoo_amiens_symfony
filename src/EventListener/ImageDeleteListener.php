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

        $filesToRemove = [];
        $filesToRemove[] = $this->imagesDirectory . '/' . $image->getEntityType().'/'. $image->getFilenameWithExtension();
        $filesToRemove[] = $this->imagesDirectory . '/' . $image->getEntityType().'/' . $image->getFilenameWithExtension('thumb');
        foreach ($filesToRemove as $filePath) {
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }


    }
}

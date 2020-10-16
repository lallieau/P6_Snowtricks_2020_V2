<?php


namespace App\EventListener;


use App\Entity\Image;
use App\Service\FileUploader;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ImageRemoveListener
{
    private $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if(!$entity instanceof Image)
        {
            return;
        }

        $this->fileUploader->removeUploadedFile($entity->getName());
    }


}
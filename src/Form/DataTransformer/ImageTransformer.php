<?php


namespace App\Form\DataTransformer;


use App\Entity\Image;
use App\Service\FileUploader;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ImageTransformer implements \Symfony\Component\Form\DataTransformerInterface
{
    private $fileUploader;

    public function __construct(FileUploader $fileUploader)
    {
        $this->fileUploader = $fileUploader;
    }

    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        if(null === $value)
        {
            return null;
        }
       return $this->fileUploader->getUploadedFile($value);
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($value)
    {
        $filename = $this->fileUploader->upload($value);
        return $filename;
    }
}

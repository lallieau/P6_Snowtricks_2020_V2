<?php

namespace App\Form;

use App\Entity\Image;
use App\Form\DataTransformer\ImageTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ImageType extends AbstractType
{
    private $transformer;

    public function __construct(ImageTransformer $imageTransformer)
    {
        $this->transformer = $imageTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', FileType::class,[
                'label' => 'Image (jpeg, png)',
                'mapped' => true,
            ]);
        $builder->get('name')
            ->addModelTransformer($this->transformer)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
            'allow_extra_fields' => true,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Image;
use App\Form\ImageType;
use App\Form\VideoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['required' => true])
            ->add('content', TextareaType::class, ['required' => true])
            ->add('images', CollectionType::class,[
                'entry_type' => ImageType::class,
                'prototype'	=> true,
                'required' => false,
                'allow_add' => true,
                'allow_delete'=> true,
                'by_reference' => false,
            ])
            ->add('videos', CollectionType::class,[
                'entry_type' => VideoType::class,
                'prototype'	=> true,
                'required' => false,
                'allow_add' => true,
                'allow_delete'=> true,
                'mapped' => false,
                'by_reference' => false,
            ])
            ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}

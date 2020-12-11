<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\FigType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class Trick1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('figType', EntityType::class, [
                'class' => FigType::class,
                'choice_label' => 'name',
                'label' => 'Type du trick',
            ])
            ->add('description')
            ->add('image', FileType::class, [
                'label' => 'Image principale du trick (.jpg)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Le fichier n\'est pas valide',                    ])
                ]
            ])
            ->add('video1', UrlType::class, [
                'label' => 'Video du trick',
                'mapped' => false,
                'required' => false,
            ])
            //->add('createdAt')
            //->add('editAt')
            
            ->add('image1', FileType::class, [
                'label' => 'Image du trick (.jpg)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Le fichier n\'est pas valide',                    ])
                ]
            ])
            ->add('image2', FileType::class, [
                'label' => 'Image du trick (.jpg)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Le fichier n\'est pas valide',                    ])
                ]
            ])
            ->add('image3', FileType::class, [
                'label' => 'Image du trick (.jpg)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Le fichier n\'est pas valide',                    ])
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}

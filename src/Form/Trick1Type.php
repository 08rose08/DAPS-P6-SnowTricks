<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\FigType;
use App\Form\VideoTrickType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

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
            ->add('videos', CollectionType::class, [
                'entry_type' => VideoTrickType::class,
                // en ajouter autant qu'on veut
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'mapped'=> false,
            ])
            ->add('imageTricks', CollectionType::class, [
                'entry_type' => ImageTrickType::class,
                // en ajouter autant qu'on veut
                'allow_add' => true,
                'allow_delete' => true,
                'required' => false,
                'mapped'=> false,
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

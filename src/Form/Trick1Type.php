<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\FigType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('image')
            ->add('video')
            //->add('createdAt')
            //->add('editAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}

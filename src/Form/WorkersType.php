<?php

namespace App\Form;

use App\Entity\Workers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('company')
            ->add('role')
            ->add('salary')
            ->add('series')
            ->add('birthday')
            ->add('age')
            ->add('height')
            ->add('weight')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Workers::class,
        ]);
    }
}

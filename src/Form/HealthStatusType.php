<?php

namespace App\Form;

use App\Entity\HealthStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class HealthStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',null,['label'=>'Nom de l\'état de santé'])
            ->add('description',null,['label'=>'Description de l\'état de santé'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HealthStatus::class,
        ]);
    }
}

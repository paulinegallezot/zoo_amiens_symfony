<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\AnimalFood;
use App\Entity\healthStatus;
use App\Entity\MedicalReport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedicalReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('animal', EntityType::class, [
                'class' => Animal::class,
                'choice_label' => 'name',
                'label'=>'Nom de l\'animal',
                'attr' => [
                    'data-control' => 'select2',
                ]
            ])
            ->add('visitedAt', null, [
                'widget' => 'single_text',
                'label'=>"Date de la consultation"
            ])
            ->add('healthStatus', EntityType::class, [
                'class' => HealthStatus::class,
                'choice_label' => 'name',
                'label'=>'Etat de santé',
                'attr' => [
                    'data-control' => 'select2',

                ]
            ])
            ->add('review',null,['label'=>"Avis médical"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MedicalReport::class,
        ]);
    }
}

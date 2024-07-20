<?php

namespace App\Form;

use App\Entity\Habitat;
use App\Entity\HabitatReport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HabitatReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('habitat', EntityType::class, [
                'class' => Habitat::class,
                'choice_label' => 'name',
                'label'=>'Habitat',
                'attr' => [
                    'data-control' => 'select2',
                ]
            ])
/*            ->add('createdAt', null, [
                'widget' => 'single_text',
                'label'=>"Date de creation"
            ])*/

            ->add('review',null,['label'=>"Avis"])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HabitatReport::class,
        ]);
    }
}

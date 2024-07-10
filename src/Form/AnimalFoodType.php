<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\AnimalFood;
use App\Entity\Food;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class AnimalFoodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

            $builder
                ->add('animal', EntityType::class, [
                    'class' => Animal::class,
                    'choice_label' => 'name',
                ])
                ->add('setAt', DateTimeType::class, [
                    'widget' => 'single_text',
                    'input' => 'datetime_immutable',
                    'required' => true,
                    'label' => 'Date de distribution',
                    'data' => new \DateTimeImmutable()
                ])

                ->add('food', EntityType::class, [
                    'class' => Food::class,
                    'choice_label' => 'name',
                    'label' => 'Alimentation',

                ])
                ->add('quantityInGrams',null,['label'=>"Quantité en grammes"]);



    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AnimalFood::class,

        ]);

    }
}

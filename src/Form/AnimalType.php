<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Race;
use App\Entity\Habitat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',null,['label'=>'Prénom'])
            ->add('race', EntityType::class, [
                'class' => race::class,
                'choice_label' => 'name',
            ])
            ->add('habitat', EntityType::class, [
                'class' => habitat::class,
                'choice_label' => 'name',
            ])
            ->add('image', FileType::class, [
                'label' => 'Ajouter une image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '400024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide',
                    ])
                ],
                'attr' => [
                    'accept' => 'image/jpeg,image/png,image/webp', // Accepte uniquement ces types de fichiers
                ],
            ])


            //->add('slug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}

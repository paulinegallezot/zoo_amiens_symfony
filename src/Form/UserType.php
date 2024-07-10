<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {



        $builder
            ->add('lastname',null,['label'=>'Nom'])
            ->add('firstname',null,['label'=>'Prénom'])
            ->add('email',null,['label'=>'Email'])
            //->add('roles')
            ->add('password',PasswordType::class,[
                'label'=>'Mot de passe',
                'help' => 'Entrer un mot de passe entre 8 et 32 caratères '.$options['password_edit_or_new'],
                'mapped' => false,
                'required' => $options['password_required'],
                'attr' => ['autocomplete' => 'new-password']])
        ->add('send_email', CheckboxType::class, [
            'label'    => 'Envoyer l\'e-mail de confirmation d\'inscription',
            'required' => false,
            'mapped' => false,
        ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'password_required' => true,
            'password_edit_or_new' => ''
        ]);

        $resolver->setAllowedTypes('password_required', 'bool');
        $resolver->setAllowedTypes('password_edit_or_new', 'string');
    }
}

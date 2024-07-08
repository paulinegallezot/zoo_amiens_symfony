<?php
// src/DataFixtures/UserFixtures.php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $users = [
            ['Garcia', 'José', 'jose.garcia@exemple.net', 'garciajose', ['ROLE_ADMIN']],
            ['Dupont', 'Marie', 'marie.dupont@exemple.net', 'dupontmarie', ['ROLE_VETO']],
            ['Bernard', 'Luc', 'luc.bernard@exemple.net', 'bernardluc', ['ROLE_VETO']],
            ['Lefèvre', 'Sophie', 'sophie.lefevre@exemple.net', 'lefevresophie', ['ROLE_VETO']],
            ['Martin', 'Jean', 'jean.martin@exemple.net', 'martinjean', ['ROLE_VETO']],
            ['Petit', 'Claire', 'claire.petit@exemple.net', 'petitclaire', ['ROLE_VETO']],
            ['Moreau', 'Thomas', 'thomas.moreau@exemple.net', 'moreauthomas', ['ROLE_EMPLOYE']],
            ['Dubois', 'Julie', 'julie.dubois@exemple.net', 'duboisjulie', ['ROLE_EMPLOYE']],
            ['Lemoine', 'Paul', 'paul.lemoine@exemple.net', 'lemoinepaul', ['ROLE_EMPLOYE']],
            ['Renard', 'Élise', 'elise.renard@exemple.net', 'renardelise', ['ROLE_EMPLOYE']],
        ];

        foreach ($users as $data) {
            $user = new User();
            $user->setFirstname($data[1]);
            $user->setLastname($data[0]);
            $user->setEmail($data[2]);
            $user->setRoles($data[4]);

            // Hacher le mot de passe
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $data[3]
            );
            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        $manager->flush();
    }
}

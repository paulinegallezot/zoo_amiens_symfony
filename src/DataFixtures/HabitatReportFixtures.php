<?php
namespace App\DataFixtures;


use App\Entity\Habitat;

use App\Entity\HabitatReport;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;


class HabitatReportFixtures extends Fixture implements DependentFixtureInterface
{




    public function load(ObjectManager $manager)
    {

        $faker = Factory::create();

       
        $habitats = $manager->getRepository(Habitat::class)->findAll();
     

        $users = [];
        foreach ($manager->getRepository(User::class)->findByRole('ROLE_VETO') as $user) {
            $users[] = $user;
        }

      

        for ($i = 0; $i < 10; $i++) {

            $habitatReport = new HabitatReport();

            $habitat = $habitats[array_rand($habitats)];
            $user = $users[array_rand($users)];

            $habitatReport->setHabitat($habitat);
            $habitatReport->setUser($user);
            $habitatReport->setReview($faker->paragraph(3));
            $habitatReport->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 years', 'now')));

            $manager->persist($habitatReport);
        }



        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            HabitatFixtures::class,
            UserFixtures::class,

        ];
    }
}

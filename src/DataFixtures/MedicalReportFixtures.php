<?php
namespace App\DataFixtures;

use App\Entity\Animal;
use App\Entity\Food;
use App\Entity\HealthStatus;
use App\Entity\MedicalReport;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;


class MedicalReportFixtures extends Fixture implements DependentFixtureInterface
{




    public function load(ObjectManager $manager)
    {

        $faker = Factory::create();

        $animals = [];
        foreach ($manager->getRepository(Animal::class)->findAll() as $animal) {
            $animals[] = $animal;
        }

        $users = [];
        foreach ($manager->getRepository(User::class)->findByRole('ROLE_VETO') as $user) {
            $users[] = $user;
        }

        $healthStatuses = [];
        foreach ($manager->getRepository(HealthStatus::class)->findAll() as $healthStatus) {
            $healthStatuses[] = $healthStatus;
        }

        $foods = [];
        foreach ($manager->getRepository(Food::class)->findAll() as $food) {
            $foods[] = $food;
        }

        for ($i = 0; $i < 40; $i++) {

            $medicalReport = new MedicalReport();

            $animal = $animals[array_rand($animals)];
            $user = $users[array_rand($users)];
            $healthStatus = $healthStatuses[array_rand($healthStatuses)];

            $medicalReport->setAnimal($animal);
            $medicalReport->setUser($user);
            $medicalReport->setHealthStatus($healthStatus);
            $medicalReport->setReview($faker->paragraph(3));
            $medicalReport->setFood($food);
            $medicalReport->setQuantityInGrams($faker->numberBetween(100, 5000));
            $medicalReport->setVisitedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 years', 'now')));


            $manager->persist($medicalReport);
        }



        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AnimalFixtures::class,
            UserFixtures::class,
            HealthStatusFixtures::class,
        ];
    }
}

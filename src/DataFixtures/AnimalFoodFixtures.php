<?php
namespace App\DataFixtures;

use App\Entity\Animal;
use App\Entity\AnimalFood;
use App\Entity\AnimalImage;
use App\Entity\Food;
use App\Entity\Habitat;
use App\Entity\Race;
use App\Entity\User;
use App\Service\ImageHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AnimalFoodFixtures extends Fixture implements DependentFixtureInterface
{




    public function load(ObjectManager $manager)
    {

        $faker = Factory::create();

        $animals = [];
        foreach ($manager->getRepository(Animal::class)->findAll() as $animal) {
            $animals[] = $animal;
        }

        $user = [];
        foreach ($manager->getRepository(User::class)->findByRoles(['ROLE_EMPLOYE','ROLE_VETO']) as $user) {
            $users[] = $user;
        }

        $foods = [];
        foreach ($manager->getRepository(Food::class)->findAll() as $food) {
            $foods[] = $food;
        }

        for ($i = 0; $i < 20; $i++) {

            $animalfood = new AnimalFood();

            $animal = $animals[array_rand($animals)];
            $user = $users[array_rand($users)];
            $food = $foods[array_rand($foods)];

            $animalfood->setAnimal($animal);
            $animalfood->setUser($user);
            $animalfood->setFood($food);
            $animalfood->setQuantityInGrams($faker->numberBetween(100, 5000));
            $animalfood->setSetAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 years', 'now')));


            $manager->persist($animalfood);
        }



        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AnimalFixtures::class,
            UserFixtures::class,
            FoodFixtures::class,
        ];
    }
}

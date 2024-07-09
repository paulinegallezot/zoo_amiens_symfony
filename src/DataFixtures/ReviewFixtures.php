<?php
// src/DataFixtures/ReviewFixtures.php

namespace App\DataFixtures;

use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Faker\Factory;

class ReviewFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $review = new Review();
            $review->setPseudo($faker->name)
                ->setContent($faker->paragraph)
                ->setPublished($faker->boolean)
                ->setPublishedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 years', 'now')))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-1 years', 'now')));


            $manager->persist($review);
        }

        $manager->flush();
    }
    public static function getGroups(): array
    {
        return ['review'];
    }
}

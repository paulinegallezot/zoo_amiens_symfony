<?php
namespace App\DataFixtures;

use App\Entity\Race;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RaceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $races = [
            ['Gorille des montagnes'],
            ['Flamant rose'],
            ['Panda géant'],
            ['Tigre de Sibérie'],
            ['Lion blanc'],
            ['Lynx'],
            ['Panthère des neiges'],
            ['Ours polaire'],
            ['Panda roux'],
            ['Loup arctique'],
            ['Loutre'],
            ['Puma'],
            ['Ours brun'],
            ['Léopard des neiges'],
            ['Lion'],
            ['Guépard'],
            ['Jaguar'],
            ['Léopard'],
            ['Tigre']
        ];
        foreach ($races as $data) {
            $race=new Race();
            $race->setName($data[0]);
            $manager->persist($race);
        }
        $manager->flush();
    }
}

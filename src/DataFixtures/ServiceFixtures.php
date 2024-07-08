<?php
namespace App\DataFixtures;

use App\Entity\Service;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ServiceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $services = [
            ['Restauration', "Découvrez notre service de restauration haut de gamme, offrant une variété de plats délicieux et adaptés à tous les goûts.\nSavourez nos spécialités préparées avec des ingrédients frais et locaux, dans un cadre agréable et convivial.\nQue vous soyez en famille ou entre amis, notre restaurant saura satisfaire toutes vos envies."],
            ['Visite des habitats', "Plongez au cœur de la vie sauvage avec notre visite des habitats.\nProfitez d'une expérience immersive unique où vous pourrez observer les animaux dans leur environnement naturel.\nInclut une visite guidée gratuite par un expert passionné, qui partagera avec vous des anecdotes fascinantes et des informations précieuses sur nos résidents."],
            ['Visite du zoo', "Explorez notre zoo à bord d'un charmant petit train.\nProfitez d'une visite confortable et relaxante tout en découvrant la diversité de notre faune.\nCe tour en train est parfait pour les familles et vous permettra de couvrir une grande partie du parc sans vous fatiguer.\nUne expérience inoubliable pour petits et grands !"],
        ];


        foreach ($services as $data) {
            $service=new Service();
            $service->setName($data[0]);
            $service->setDescription($data[1]);
            $manager->persist($service);
        }
        $manager->flush();
    }
}

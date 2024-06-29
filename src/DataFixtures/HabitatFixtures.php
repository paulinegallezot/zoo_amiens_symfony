<?php
namespace App\DataFixtures;

use App\Entity\Habitat;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HabitatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $habitats = [
            ['Montagne', 'Un habitat rude et sec, caractérisé par des altitudes élevées et des températures souvent froides.'],
            ['Désert', 'Hostile chaud en journée et froid la nuit, avec peu de précipitations et une végétation clairsemée.'],
            ['Forêt', 'Un habitat densément boisé, abritant une grande diversité de flore et de faune.'],
            ['Jungle', 'Une forêt tropicale dense avec une biodiversité riche et un climat chaud et humide.'],
            ['Savane', 'Un habitat ouvert avec des herbes hautes et quelques arbres, caractérisé par des saisons sèches et humides.'],
            ['Océan', 'Un vaste étendu d\'eau salée qui abrite une immense diversité de vie marine.'],
            ['Plage', 'Un habitat côtier de sable ou de galets, où la terre rencontre l\'océan ou la mer.'],
            ['Banquise', 'Un habitat glacé et hostile, constitué de couches de glace flottantes sur les océans polaires.'],
            ['Rivière', 'Un cours d\'eau douce qui coule de manière continue vers un océan, un lac ou une autre rivière.'],
            ['Lac', 'Une grande étendue d\'eau douce entourée de terres, souvent riche en vie aquatique.'],
            ['Marais', 'Un habitat humide et marécageux, souvent peu profond, abritant une diversité de plantes et d\'animaux.'],
            ['Grotte', 'Un espace souterrain creusé dans la roche, souvent abritant des espèces spécifiques adaptées à l\'obscurité.'],
            ['Volcan', 'Un habitat autour d\'un volcan actif ou inactif, caractérisé par des sols riches en minéraux.'],
            ['Plaine', 'Une vaste étendue de terrain plat ou légèrement vallonné, souvent utilisée pour l\'agriculture.'],
            ['Mangrove', 'Un habitat côtier tropical avec des arbres adaptés aux conditions salines, offrant un refuge à de nombreuses espèces.'],
            ['Canyon', 'Un profond ravin creusé par l\'érosion, souvent spectaculaire avec des parois abruptes.'],
            ['Steppe', 'Une vaste étendue de plaines herbeuses semi-arides, souvent sujettes à des températures extrêmes.'],
            ['Toundra', 'Un habitat froid avec des sols gelés en permanence, supportant une végétation basse et peu d\'arbres.'],
        ];

        foreach ($habitats as $data) {
            $habitat=new Habitat();
            $habitat->setName($data[0]);
            $habitat->setDescription($data[1]);
            $manager->persist($habitat);
        }
        $manager->flush();
    }
}

<?php
namespace App\DataFixtures;

use App\Entity\HealthStatus;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HealthStatusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $healthStatus = [
            ['Blessé','L\'animal a subi une blessure, qu\'elle soit mineure ou majeure.'],
            ['En bonne santé','L\'animal est en bonne santé.'],
            ['Malade','L\'animal est malade.'],
            ['En voie de disparition','L\'animal est en voie de disparition.'],
            ['En observation','L\'animal est en observation.'],
            ['En soin','L\'animal est en soin.'],
            ['En quarantaine','L\'animal est en quarantaine.'],
            ['En convalescence','L\'animal est en convalescence.'],
            ['En rééducation','L\'animal est en rééducation.'],
            ['En gestation','L\'animal est en période de gestation.'],
            ['En sevrage','L\'animal est en période de sevrage.'],
            ['En hibernation','L\'animal est en hibernation'],
            ['En migration','L\'animal est en migration.'],
            ['En rut','L\'animal est en période de rut.'],
            ['En chaleur','L\'animal est en période de chaleur.'],
            ['Post-partum','L\'animal vient de donner naissance et est en période de récupération.'],
            ['Problèmes comportementaux','L\'animal montre des comportements anormaux, souvent dus à des problèmes psychologiques ou environnementaux.'],
            ['Sous traitement vétérinaire','L\'animal est sous traitement vétérinaire pour une maladie ou une blessure.'],
            ['En période de ponte','L\'animal est en période de ponte.'],
            ['Stressé','L\'animal montre des signes de stress, ce qui peut être dû à l\'environnement, aux interactions sociales ou à d\'autres facteurs.'],
            ['En période de reproduction','L\'animal est en période de reproduction.'],
            ['En période de nidification','L\'animal est en période de nidification.'],
            ['En période de ponte','L\'animal est en période de ponte.'],
            ['En période de mue','L\'animal est en période de mue.'],
            ['En période de reproduction','L\'animal est en période de reproduction.'],
            ['Vieillissant','L\'animal montre des signes de vieillissement, avec des possibles problèmes de santé liés à l\'âge.']
        ];
        foreach ($healthStatus as $data) {
            $healthStatus=new HealthStatus();
            $healthStatus->setName($data[0]);
            $healthStatus->setDescription($data[1]);
            $manager->persist($healthStatus);
        }
        $manager->flush();
    }
}

<?php
namespace App\DataFixtures;

use App\Entity\Food;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FoodFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $foods = [
            ['Boeuf en carcasse',''],
            ['Boeuf en morceaux',''],
            ['Boeuf haché',''],
            ['Veau en carcasse',''],
            ['Veau en morceaux',''],
            ['Veau haché',''],
            ['Porc en carcasse',''],
            ['Porc en morceaux',''],
            ['Porc haché',''],
            ['Agneau en carcasse',''],
            ['Agneau en morceaux',''],
            ['Agneau haché',''],
            ['Mouton en carcasse',''],
            ['Mouton en morceaux',''],
            ['Mouton haché',''],
            ['Assortiment de fruits 1','Pommes, Bananes, Oranges, Poires, Raisins, Pastèques, Mangues et Ananas'],
            ['Assortiment de fruits 2','Fraises, Framboises, Mûres, Myrtilles, Cerises, Groseilles, Kiwis et Grenades'],
            ['Assortiment de legumes 1','Carottes, Pommes de terre, Tomates, Courgettes, Poivrons, Aubergines, Concombres et Radis'],
            ['Assortiment de legumes 2','Haricots verts, Petits pois, Brocolis, Choux-fleurs, Epinards, Salades, Endives et Céleris'],
            ['Poulet en carcasse',''],
            ['Poulet en morceaux',''],
            ['Dinde en carcasse',''],
            ['Dinde en morceaux',''],
            ['Dinde hachée',''],

              ];
        foreach ($foods as $data) {
            $food=new Food();
            $food->setName($data[0]);
            $food->setDescription($data[1]);
            $manager->persist($food);
        }
        $manager->flush();
    }
}

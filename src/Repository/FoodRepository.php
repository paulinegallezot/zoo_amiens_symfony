<?php

namespace App\Repository;

use App\Entity\Food;
use App\Repository\Traits\FindInDatatableTrait;
use App\Service\RepositoryHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Food>
 */
class FoodRepository extends ServiceEntityRepository
{
    private $repositoryHelper;
    use FindInDatatableTrait;

    public function __construct(ManagerRegistry $registry,RepositoryHelper $repositoryHelper)
    {
        parent::__construct($registry, Food::class);
        $this->repositoryHelper = $repositoryHelper;
    }


}

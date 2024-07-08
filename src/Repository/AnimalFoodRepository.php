<?php

namespace App\Repository;

use App\Entity\AnimalFood;
use App\Repository\Traits\FindInDatatableTrait;
use App\Service\RepositoryHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class AnimalFoodRepository extends ServiceEntityRepository
{
    private RepositoryHelper $repositoryHelper;
    use FindInDatatableTrait;

    public function __construct(ManagerRegistry $registry,RepositoryHelper $repositoryHelper)
    {
        parent::__construct($registry, AnimalFood::class);
        $this->repositoryHelper = $repositoryHelper;
    }


}

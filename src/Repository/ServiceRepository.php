<?php

namespace App\Repository;

use App\Entity\Service;
use App\Repository\Traits\FindInDatatableTrait;
use App\Service\RepositoryHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class ServiceRepository extends ServiceEntityRepository
{
    private $repositoryHelper;
    use FindInDatatableTrait;

    public function __construct(ManagerRegistry $registry,RepositoryHelper $repositoryHelper)
    {
        parent::__construct($registry, Service::class);
        $this->repositoryHelper = $repositoryHelper;
    }

}

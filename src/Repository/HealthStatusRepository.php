<?php

namespace App\Repository;

use App\Entity\HealthStatus;
use App\Repository\Traits\FindInDatatableTrait;
use App\Service\RepositoryHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HealthStatus>
 */
class HealthStatusRepository extends ServiceEntityRepository
{
    private $repositoryHelper;
    use FindInDatatableTrait;

    public function __construct(ManagerRegistry $registry,RepositoryHelper $repositoryHelper)
    {
        parent::__construct($registry, HealthStatus::class);
        $this->repositoryHelper = $repositoryHelper;
    }

}

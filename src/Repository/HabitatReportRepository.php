<?php

namespace App\Repository;

use App\Entity\HabitatReport;
use App\Repository\Traits\FindInDatatableTrait;
use App\Service\RepositoryHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HabitatReport>
 */
class HabitatReportRepository extends ServiceEntityRepository
{

    private $repositoryHelper;
    use FindInDatatableTrait;

    public function __construct(ManagerRegistry $registry,RepositoryHelper $repositoryHelper)
    {
        parent::__construct($registry, HabitatReport::class);
        $this->repositoryHelper = $repositoryHelper;
    }


}

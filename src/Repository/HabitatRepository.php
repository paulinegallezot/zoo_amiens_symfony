<?php

namespace App\Repository;

use App\Entity\Habitat;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Service\RepositoryHelper;

/**
 * @extends ServiceEntityRepository<Habitat>
 */
class HabitatRepository extends ServiceEntityRepository
{
    private RepositoryHelper $repositoryHelper;

    // on n'utilise pas le trait FindInDatatableTrait car il y a des images


    public function __construct(ManagerRegistry $registry,RepositoryHelper $repositoryHelper)
    {
        parent::__construct($registry, Habitat::class);
        $this->repositoryHelper = $repositoryHelper;
    }
    public function findInDatatable($query)
    {
        $qb = $this->createQueryBuilder('a');

        $qb->select('a');

        $this->repositoryHelper->init($qb,$query);
        $this->repositoryHelper->applySearch();
        $this->repositoryHelper->applyFilters();
        $recordsFiltered = $this->repositoryHelper->getRecordsFiltered();
        $qb->leftJoin('a.images', 'ai');
        $qb->groupBy('a.id');
        $this->repositoryHelper->applyOrder();
        $this->repositoryHelper->applyLimitAndOffset();

        return [$qb->getQuery()->getResult(), $recordsFiltered];
    }
}

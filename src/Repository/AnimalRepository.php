<?php

namespace App\Repository;

use App\Entity\Animal;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Service\RepositoryHelper;
/**
 * @extends ServiceEntityRepository<Animal>
 *
 * @method Animal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Animal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Animal[]    findAll()
 * @method Animal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimalRepository extends ServiceEntityRepository
{
    private RepositoryHelper $repositoryHelper;

    public function __construct(ManagerRegistry $registry,RepositoryHelper $repositoryHelper)
    {
        parent::__construct($registry, Animal::class);
        $this->repositoryHelper = $repositoryHelper;
    }

    public function findAllSortedByName()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findForDatatable($query)
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

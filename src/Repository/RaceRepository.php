<?php

namespace App\Repository;

use App\Entity\Race;
use App\Service\RepositoryHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Race>
 *
 * @method Race|null find($id, $lockMode = null, $lockVersion = null)
 * @method Race|null findOneBy(array $criteria, array $orderBy = null)
 * @method Race[]    findAll()
 * @method Race[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaceRepository extends ServiceEntityRepository
{
    private $repositoryHelper;
    public function __construct(ManagerRegistry $registry,RepositoryHelper $repositoryHelper)
    {
        parent::__construct($registry, Race::class);
        $this->repositoryHelper = $repositoryHelper;
    }


    public function findForDatatable($query)
    {

        $qb = $this->createQueryBuilder('a');
        $qb->select('a');

        $this->repositoryHelper->init($qb,$query);
        $this->repositoryHelper->applySearch();
        //$this->repositoryHelper->applyFilters();
        $recordsFiltered = $this->repositoryHelper->getRecordsFiltered();
        //$qb->groupBy('a.id');
        $this->repositoryHelper->applyOrder();
        $this->repositoryHelper->applyLimitAndOfset();

        return [$qb->getQuery()->getResult(), $recordsFiltered];
    }





    //    /**
    //     * @return Race[] Returns an array of Race objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Race
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

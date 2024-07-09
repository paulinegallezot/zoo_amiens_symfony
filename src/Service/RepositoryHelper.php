<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

class RepositoryHelper
{
    private EntityManagerInterface $entityManager;

    private $request;
    private $filtermatch = null;
    private $limit = 10;
    private $offset = 0;
    private $searchCols =[] ;
    private $orderBy = [];
    private $qb;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function init(QueryBuilder $qb,$request){
        $this->qb = $qb;
        $this->request = $request;

        $this->filtermatch = $request->all('filtermatch');
        $this->limit = $request->get('length');
        $this->offset = $request->get('start');
        $this->getSearchColumnsAndOrderBy();
    }
    public function applySearch(): void
    {

        $searchValueArray = $this->request->all('search');
        $searchValue = $searchValueArray['value'] ?? null;


        if ($searchValue) {
            $expr = $this->qb->expr();
            $orConditions = [];

            foreach ($this->searchCols as $col) {
                $orConditions[] = $expr->like('a.' . $col, ':searchTerm');
            }

            // Construire la clause OR
            $orClause = $expr->orX(...$orConditions);

            // Ajouter la clause OR comme une condition AND
            $this->qb->andWhere($orClause)
                ->setParameter('searchTerm', '%' . $searchValue . '%');
        }


    }
    public function applyFilters(): void
    {

        foreach ($this->filtermatch as $filter => $value) {
            if ($value) {
                $this->qb->join('a.' . $filter, $filter)
                    ->andWhere($filter . '.id = :'.$filter.'_value')
                    ->setParameter($filter.'_value', $value);
            }
        }
    }

    public function applyFiltersByValue(): void
    {

        $filterByValue = $this->request->all('filterByValue');

        if ($filterByValue) {
            foreach ($filterByValue as $filter => $value) {
                if ($value) {

                    if ($value=='false') $value = '0'; // pour les bollean
                    if ($value=='true') $value = '1'; // pour les bollean
                    $valueParam = $filter.'_value';

                    $this->qb
                        ->andWhere('a.'.$filter . " LIKE :$valueParam")
                        ->setParameter($valueParam, '%'.$value.'%');
                }
            }
        }

    }
    public function applyFiltersByDates(): void
    {
        $filterByDates = $this->request->all('filterByDates');
        if ($filterByDates) {
            foreach ($filterByDates as $filter => $value) {

                $startDate = new \DateTimeImmutable($value[0]. ' 00:00:00');
                $endDate = new \DateTimeImmutable($value[1]. ' 23:59:59');
                $startParam = $filter . '_start_date';
                $endParam = $filter . '_end_date';

                $this->qb->andWhere("a.$filter BETWEEN :$startParam AND :$endParam")
                    ->setParameter($startParam, $startDate)
                    ->setParameter($endParam, $endDate);

            }
        }
    }

    public function getRecordsFiltered() : int
    {
        $countQb = clone $this->qb;
        $countQb->select('COUNT(a.id)');
        $countQuery = $countQb->getQuery();

        return (int) $countQuery->getSingleScalarResult();
    }
    private function getSearchColumnsAndOrderBy(): void
    {
        $columns = $this->request->all()['columns'];

        foreach ($columns as $field) {
            if ($field['searchable'] === 'true') {
                $this->searchCols[] = $field['data'];
            }
        }

        $order = isset($this->request->all()['order']) ? $this->request->all()['order'] : [];
        foreach($order as $field) {
            $columnIndex = $field['column'] *1;
            $this->orderBy['a.'.$columns[$columnIndex]['data']] =$field['dir'];
        }

    }
    public  function applyOrder(): void
    {

        foreach ($this->orderBy as $column => $direction) {
            $this->qb->orderBy( $column, $direction);
        }

    }
    public function applyLimitAndOffset(): void
    {
        if ($this->limit !== null) {
                $this->qb->setMaxResults($this->limit);
        }
        if ($this->offset !== null) {
            $this->qb->setFirstResult($this->offset);
        }
    }



}

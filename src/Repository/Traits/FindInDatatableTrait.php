<?php
namespace App\Repository\Traits;



trait FindInDatatableTrait
{
    public function findInDatatable($query): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb->select('a');

        $this->repositoryHelper->init($qb, $query);
        $this->repositoryHelper->applySearch();
        $this->repositoryHelper->applyFilters();
        $this->repositoryHelper->applyFiltersByDates();
        $this->repositoryHelper->applyFiltersByValue();
        $recordsFiltered = $this->repositoryHelper->getRecordsFiltered();

        $this->repositoryHelper->applyOrder();
        $this->repositoryHelper->applyLimitAndOffset();

        return [$qb->getQuery()->getResult(), $recordsFiltered];
    }
}

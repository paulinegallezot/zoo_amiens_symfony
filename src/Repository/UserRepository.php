<?php

namespace App\Repository;

use App\Entity\User;
use App\Repository\Traits\FindInDatatableTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use App\Service\RepositoryHelper;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{

    private $repositoryHelper;


    public function __construct(ManagerRegistry $registry,RepositoryHelper $repositoryHelper)
    {
        parent::__construct($registry, User::class);
        $this->repositoryHelper = $repositoryHelper;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findInDatatable($query): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb->select('a');
        $qb->where('a.roles NOT LIKE :role')
            ->setParameter('role', '%ROLE_ADMIN%');// ne jamais afficher les ROLE_ADMIN
        $this->repositoryHelper->init($qb, $query);
        $this->repositoryHelper->applySearch();
        $this->repositoryHelper->applyFiltersByValue();

        $recordsFiltered = $this->repositoryHelper->getRecordsFiltered();

        $this->repositoryHelper->applyOrder();
        $this->repositoryHelper->applyLimitAndOffset();

        return [$qb->getQuery()->getResult(), $recordsFiltered];
    }

    public function findByRole(string $role): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :role')
            ->setParameter('role', '%"'.$role.'"%')
            ->orderBy('u.firstname', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function findByRoles(array $roles): array
    {
        $qb = $this->createQueryBuilder('u');

        foreach ($roles as $key => $role) {
            $qb->orWhere('u.roles LIKE :role' . $key)
                ->setParameter('role' . $key, '%"' . $role . '"%');
        }

        return $qb->orderBy('u.firstname', 'ASC')
            ->getQuery()
            ->getResult();
    }

}

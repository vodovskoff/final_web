<?php

namespace App\Repository;

use App\Entity\ActionType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActionType>
 *
 * @method ActionType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActionType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActionType[]    findAll()
 * @method ActionType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActionType::class);
    }

    public function add(ActionType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ActionType $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

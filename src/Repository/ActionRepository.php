<?php

namespace App\Repository;

use App\Entity\Action;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @extends ServiceEntityRepository<Action>
 *
 * @method Action|null find($id, $lockMode = null, $lockVersion = null)
 * @method Action|null findOneBy(array $criteria, array $orderBy = null)
 * @method Action[]    findAll()
 * @method Action[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Action::class);
    }

    public function add(Action $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Action $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Action[] Returns an array of Action objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function countActionsByTypeAndUser(int $managerId, int $actionType, \DateTime $month): int
    {
        $from = $month->format("Y-m-01");
        $to = $month->format("Y-m-t");

        $fromFormat = \DateTime::createFromFormat("Y-m-d", $from)->setTime(0 ,0);
        $toFormat = \DateTime::createFromFormat("Y-m-d", $to)->setTime(23, 59);

        $q =  $this->createQueryBuilder('a')
        ->join('a.manager', 'm')
        ->join('a.action_type', 'at')
        ->andWhere('at.id = :at_id')
        ->andWhere('m.id = :m_id')
        ->andWhere('a.date BETWEEN :from AND :to')
        ->setParameter('at_id', $actionType)
        ->setParameter('m_id', $managerId)
        ->setParameter('from', $fromFormat)
        ->setParameter('to', $toFormat)
        ->getQuery()
        ->getResult();
        return count($q);
    }

    public function getByManagerAndDate(int $managerId, \DateTime $month): array
    {
        $from = $month->format("Y-m-01");
        $to = $month->format("Y-m-t");

        $fromFormat = \DateTime::createFromFormat("Y-m-d", $from)->setTime(0 ,0);
        $toFormat = \DateTime::createFromFormat("Y-m-d", $to)->setTime(23, 59);

        $q =  $this->createQueryBuilder('a')
            ->join('a.manager', 'm')
            ->andWhere('m.id = :m_id')
            ->andWhere('a.date BETWEEN :from AND :to')
            ->setParameter('m_id', $managerId)
            ->setParameter('from', $fromFormat)
            ->setParameter('to', $toFormat)
            ->getQuery()
            ->getResult();
        return $q;
    }

    public function getActionsByTypeAndManager(int $managerId, int $actionType, \DateTime $month): array
    {
        $from = $month->format("Y-m-01");
        $to = $month->format("Y-m-t");

        $fromFormat = \DateTime::createFromFormat("Y-m-d", $from)->setTime(0 ,0);
        $toFormat = \DateTime::createFromFormat("Y-m-d", $to)->setTime(23, 59);

        $q =  $this->createQueryBuilder('a')
            ->join('a.manager', 'm')
            ->join('a.action_type', 'at')
            ->andWhere('at.id = :at_id')
            ->andWhere('m.id = :m_id')
            ->andWhere('a.date BETWEEN :from AND :to')
            ->setParameter('at_id', $actionType)
            ->setParameter('m_id', $managerId)
            ->setParameter('from', $fromFormat)
            ->setParameter('to', $toFormat)
            ->getQuery()
            ->getResult();
        return $q;
    }
}

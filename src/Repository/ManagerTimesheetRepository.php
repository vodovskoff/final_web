<?php

namespace App\Repository;

use App\Entity\ManagerTimesheet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ManagerTimesheet>
 *
 * @method ManagerTimesheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method ManagerTimesheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method ManagerTimesheet[]    findAll()
 * @method ManagerTimesheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ManagerTimesheetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ManagerTimesheet::class);
    }

    public function add(ManagerTimesheet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ManagerTimesheet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return ManagerTimesheet[] Returns an array of ManagerTimesheet objects
     */
    public function countWorkingDays(int $managerId, \DateTime $month): int
    {
        $from = $month->format("Y-m-01");
        $to = $month->format("Y-m-t");

        $fromFormat = \DateTime::createFromFormat("Y-m-d", $from)->setTime(0 ,0);
        $toFormat = \DateTime::createFromFormat("Y-m-d", $to)->setTime(23, 59);

        return count($this->createQueryBuilder('m')
            ->join('m.manager', 'man')
            ->andWhere('man.id = :manager_id')
            ->andWhere('m.coming_to_work BETWEEN :from AND :to')
            ->andWhere('m.leaving_from_work BETWEEN :from AND :to')
            ->setParameter('manager_id', $managerId)
            ->setParameter('from', $fromFormat)
            ->setParameter('to', $toFormat)
            ->getQuery()
            ->getResult())
        ;
    }

//    public function findOneBySomeField($value): ?ManagerTimesheet
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

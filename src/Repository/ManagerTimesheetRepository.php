<?php

namespace App\Repository;

use App\Entity\ManagerTimesheet;
use App\Service\FormatService;
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
        list($fromFormat, $toFormat) = FormatService::formatDatesBorders($month);

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

<?php

namespace App\Repository;

use App\Entity\Pay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Array_;

/**
 * @extends ServiceEntityRepository<Pay>
 *
 * @method Pay|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pay|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pay[]    findAll()
 * @method Pay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pay::class);
    }

    public function add(Pay $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Pay $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Pay[] Returns an array of Pay objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findObjecctsByFilters(\DateTime $month, ?int $minDays, ?int $maxDays, ?int $minSells, ?int $maxSells, ?int $minDrives, ?int $maxDrives):array
    {
        $from = $month->format("Y-m-01");
        $to = $month->format("Y-m-t");
        $fromFormat = \DateTime::createFromFormat("Y-m-d", $from)->setTime(0 ,0);
        $toFormat = \DateTime::createFromFormat("Y-m-d", $to)->setTime(23, 59);

        $q = $this->createQueryBuilder('p')
            ->andWhere('p.start_of_period BETWEEN :from AND :to')
            ->andWhere('p.end_of_period BETWEEN :from AND :to');

        if ($minDrives!==null) $q=$q->andWhere('p.test_drives >= :minDrives');
        if ($maxDrives!==null) $q=$q->andWhere('p.test_drives <= :maxDrives');
        if ($minSells!==null) $q=$q->andWhere('p.sells_number >= :minSells');
        if ($maxSells!==null) $q=$q->andWhere('p.sells_number <= :maxSells');
        if ($minDays!==null) $q=$q->andWhere('p.working_days >= :minDays');
        if ($maxDays!==null) $q=$q->andWhere('p.working_days <= :maxDays');

        $q = $q->setParameter('from', $fromFormat)
            ->setParameter('to', $toFormat);

        if ($minDrives!==null) $q=$q->setParameter('minDrives', $minDrives);
        if ($maxDrives!==null) $q=$q->setParameter('maxDrives', $maxDrives);
        if ($minSells!==null) $q=$q->setParameter('minSells', $minSells);
        if ($maxSells!==null) $q=$q->setParameter('maxSells', $maxSells);
        if ($minDays!==null) $q=$q->setParameter('minDays', $minDays);
        if ($maxDays!==null) $q=$q->setParameter('maxDays', $maxDays);

        return $q->getQuery()->getResult();
    }

    public function findByFilters(\DateTime $month, ?int $minDays, ?int $maxDays, ?int $minSells, ?int $maxSells, ?int $minDrives, ?int $maxDrives):array
    {
        $from = $month->format("Y-m-01");
        $to = $month->format("Y-m-t");
        $fromFormat = \DateTime::createFromFormat("Y-m-d", $from)->setTime(0 ,0);
        $toFormat = \DateTime::createFromFormat("Y-m-d", $to)->setTime(23, 59);

        $q = $this->createQueryBuilder('p')
            ->andWhere('p.start_of_period BETWEEN :from AND :to')
            ->andWhere('p.end_of_period BETWEEN :from AND :to');

        if ($minDrives!==null) $q=$q->andWhere('p.test_drives >= :minDrives');
        if ($maxDrives!==null) $q=$q->andWhere('p.test_drives <= :maxDrives');
        if ($minSells!==null) $q=$q->andWhere('p.sells_number >= :minSells');
        if ($maxSells!==null) $q=$q->andWhere('p.sells_number <= :maxSells');
        if ($minDays!==null) $q=$q->andWhere('p.working_days >= :minDays');
        if ($maxDays!==null) $q=$q->andWhere('p.working_days <= :maxDays');

        $q = $q->setParameter('from', $fromFormat)
            ->setParameter('to', $toFormat);

        if ($minDrives!==null) $q=$q->setParameter('minDrives', $minDrives);
        if ($maxDrives!==null) $q=$q->setParameter('maxDrives', $maxDrives);
        if ($minSells!==null) $q=$q->setParameter('minSells', $minSells);
        if ($maxSells!==null) $q=$q->setParameter('maxSells', $maxSells);
        if ($minDays!==null) $q=$q->setParameter('minDays', $minDays);
        if ($maxDays!==null) $q=$q->setParameter('maxDays', $maxDays);

        return $q->getQuery()->getArrayResult();
    }

    public function findOneByManagerAndMonth(int $managerId, \DateTime $month): ?Pay
    {
        $from = $month->format("Y-m-01");
        $to = $month->format("Y-m-t");

        $fromFormat = \DateTime::createFromFormat("Y-m-d", $from)->setTime(0 ,0);
        $toFormat = \DateTime::createFromFormat("Y-m-d", $to)->setTime(23, 59);

        return $this->createQueryBuilder('p')
            ->join('p.manager', 'm')
            ->andWhere('m.id = :manager_id')
            ->andWhere('p.start_of_period BETWEEN :from AND :to')
            ->andWhere('p.end_of_period BETWEEN :from AND :to')
            ->setParameter('from', $fromFormat)
            ->setParameter('to', $toFormat)
            ->setParameter('manager_id', $managerId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findOneByManagerAndMonthArray(int $managerId, \DateTime $month): ?Array
    {
        $from = $month->format("Y-m-01");
        $to = $month->format("Y-m-t");

        $fromFormat = \DateTime::createFromFormat("Y-m-d", $from)->setTime(0 ,0);
        $toFormat = \DateTime::createFromFormat("Y-m-d", $to)->setTime(23, 59);

        return $this->createQueryBuilder('p')
            ->join('p.manager', 'm')
            ->andWhere('m.id = :manager_id')
            ->andWhere('p.start_of_period BETWEEN :from AND :to')
            ->andWhere('p.end_of_period BETWEEN :from AND :to')
            ->setParameter('from', $fromFormat)
            ->setParameter('to', $toFormat)
            ->setParameter('manager_id', $managerId)
            ->getQuery()
            ->getArrayResult();
    }
}

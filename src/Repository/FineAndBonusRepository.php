<?php

namespace App\Repository;

use App\Entity\FineAndBonus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FineAndBonus>
 *
 * @method FineAndBonus|null find($id, $lockMode = null, $lockVersion = null)
 * @method FineAndBonus|null findOneBy(array $criteria, array $orderBy = null)
 * @method FineAndBonus[]    findAll()
 * @method FineAndBonus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FineAndBonusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FineAndBonus::class);
    }

    public function add(FineAndBonus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FineAndBonus $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return FineAndBonus[] Returns an array of FineAndBonus objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findAmountByManagerAndMonth(int $managerId, \DateTime $month, int $fineOrBonus = null): float
    {
        $from = $month->format("Y-m-01");
        $to = $month->format("Y-m-t");

        $fromFormat = \DateTime::createFromFormat("Y-m-d", $from)->setTime(0 ,0);
        $toFormat = \DateTime::createFromFormat("Y-m-d", $to)->setTime(23, 59);

        $q = $this->createQueryBuilder('f')
            ->join('f.manager', 'm')
            ->select('SUM(f.amount)');

        if ($fineOrBonus and $fineOrBonus>0) {
            $q = $q->andWhere('f.amount > 0');
        } else {
            if ($fineOrBonus and $fineOrBonus<0) {
                $q = $q->andWhere('f.amount < 0');
            }
        }

        $q = $q->andWhere('m.id = :manager_id')
            ->andWhere('f.date_of_end BETWEEN :from AND :to')
            ->setParameter('manager_id', $managerId)
            ->setParameter('from', $fromFormat)
            ->setParameter('to', $toFormat)
            ->groupBy('m.id')
            ->getQuery()
            ->getOneOrNullResult()
        ;
        if($q) {
            return $q['1'];
        } else {
            return 0;
        }
    }
}

<?php

namespace App\Repository;

use App\Entity\FineAndBonus;
use App\Service\FormatService;
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
    public function __construct(ManagerRegistry $registry, FormatService $formatService)
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

    public function findByManagerAndMonth(int $managerId, \DateTime $month): array
    {
        list($fromFormat, $toFormat) = FormatService::formatDatesBorders($month);

        $q = $this->createQueryBuilder('f')
            ->join('f.manager', 'm');

        return $q->andWhere('m.id = :manager_id')
            ->andWhere('f.date_of_end BETWEEN :from AND :to')
            ->setParameter('manager_id', $managerId)
            ->setParameter('from', $fromFormat)
            ->setParameter('to', $toFormat)
            ->getQuery()
            ->getResult();
    }

    public function findAmountByManagerAndMonth(int $managerId, \DateTime $month, int $fineOrBonus = null): float
    {
        list($fromFormat, $toFormat) = FormatService::formatDatesBorders($month);

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

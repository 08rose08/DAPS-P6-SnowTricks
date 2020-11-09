<?php

namespace App\Repository;

use App\Entity\FigType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FigType|null find($id, $lockMode = null, $lockVersion = null)
 * @method FigType|null findOneBy(array $criteria, array $orderBy = null)
 * @method FigType[]    findAll()
 * @method FigType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FigTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FigType::class);
    }

    // /**
    //  * @return FigType[] Returns an array of FigType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FigType
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

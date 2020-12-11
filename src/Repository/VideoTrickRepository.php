<?php

namespace App\Repository;

use App\Entity\VideoTrick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VideoTrick|null find($id, $lockMode = null, $lockVersion = null)
 * @method VideoTrick|null findOneBy(array $criteria, array $orderBy = null)
 * @method VideoTrick[]    findAll()
 * @method VideoTrick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoTrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoTrick::class);
    }

    // /**
    //  * @return VideoTrick[] Returns an array of VideoTrick objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VideoTrick
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

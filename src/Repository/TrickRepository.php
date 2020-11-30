<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 */
 //* @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
class TrickRepository extends ServiceEntityRepository
{
    public const PAGINATOR_PER_PAGE = 5;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    // /**
    //  * @return Trick[] Returns an array of Trick objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Trick
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /*public function findByPaginator(int $rang): Paginator
    {
        $query = $this->createQueryBuilder('t')
            
            ->orderBy('t.createdAt', 'DESC')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult(0 + (self::PAGINATOR_PER_PAGE * $rang))
            ->getQuery()
        ;

        return new Paginator($query->execute());
    }*/
    public function findByPaginator(int $rang)
    {
        $query = $this->createQueryBuilder('t')
            
            ->orderBy('t.createdAt', 'DESC')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult(0 + (self::PAGINATOR_PER_PAGE * $rang))
            ->getQuery()
        ;

        return $query->execute();
    }
   

    /*public function nbComments(Trick $trick)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT COUNT(*) AS nb_comments FROM comment WHERE trick_id = :id'
        )->setParameter('id', $trick->getId());
        return $query->getResult();
    }*/

    /*public function getCommentsPage($comment1, $nbCommentsPage, $trick)
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.trick = :trick')
            ->setParameter('trick', $trick)
            ->setFirstResult($comment1)
            ->setMaxResult($nbCommentsPage)
            ->orderBy('c.createdAt' , 'DESC');

        $query = $qb->getQuery();
        return $query->execute();*/
        
        /*$entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c 
            FROM App/Entity/Comment c
            WHERE c.trick = :trick
            ORDER BY c.createdAt DESC
            LIMIT :comment1, :nbCommentsPage
            '
        )->setParameter('trick', $trick);

        return $query->getResult();

    }*/
}

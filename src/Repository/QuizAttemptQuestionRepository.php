<?php

namespace App\Repository;

use App\Entity\QuizAttemptQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuizAttemptQuestion>
 *
 * @method QuizAttemptQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizAttemptQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizAttemptQuestion[]    findAll()
 * @method QuizAttemptQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizAttemptQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizAttemptQuestion::class);
    }

    //    /**
    //     * @return QuizAttemptQuestion[] Returns an array of QuizAttemptQuestion objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?QuizAttemptQuestion
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

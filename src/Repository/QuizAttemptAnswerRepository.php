<?php

namespace App\Repository;

use App\Entity\QuizAttemptAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuizAttemptAnswer>
 *
 * @method QuizAttemptAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizAttemptAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizAttemptAnswer[]    findAll()
 * @method QuizAttemptAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizAttemptAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizAttemptAnswer::class);
    }

    //    /**
    //     * @return QuizAttemptAnswer[] Returns an array of QuizAttemptAnswer objects
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

    //    public function findOneBySomeField($value): ?QuizAttemptAnswer
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

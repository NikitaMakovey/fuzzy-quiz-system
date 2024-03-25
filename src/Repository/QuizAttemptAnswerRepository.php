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
}

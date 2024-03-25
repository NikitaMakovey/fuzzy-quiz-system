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
}

<?php

namespace App\Repository;

use App\Entity\QuizAttempt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuizAttempt>
 *
 * @method QuizAttempt|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizAttempt|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizAttempt[]    findAll()
 * @method QuizAttempt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizAttemptRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizAttempt::class);
    }

    public function findOneById(int $id): mixed
    {
        return $this->createQueryBuilder('qa')
            ->leftJoin('qa.quizAttemptQuestions', 'qaq')
            ->leftJoin('qaq.question', 'q')
            ->leftJoin('qaq.quizAttemptAnswers', 'qaa')
            ->leftJoin('qaa.answer', 'a')
            ->addSelect('qaq')
            ->addSelect('qaa')
            ->addSelect('q')
            ->addSelect('a')
            ->andWhere('qa.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult(Query::HYDRATE_ARRAY);
    }
}

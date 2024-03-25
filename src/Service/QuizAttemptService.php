<?php

namespace App\Service;

use App\Repository\QuizAttemptRepository;
use Doctrine\ORM\Query;

class QuizAttemptService
{
    public function __construct(
        private readonly QuizAttemptRepository $quizRepository
    ) {
    }

    /**
     * @note
     * Get quiz by ID
     *
     * @throws \Exception
     */
    public function getQuizAttemptById(int $id): ?array
    {
        $quizAttempt = $this->quizRepository->createQueryBuilder('qa')
            ->leftJoin('qa.quizAttemptQuestions', 'qaq')
            ->leftJoin('qaq.quizAttemptAnswers', 'qaa')
            ->leftJoin('qaa.answer', 'a')
            ->leftJoin('qaq.question', 'q')
            ->addSelect('qaq')
            ->addSelect('qaa')
            ->addSelect('q')
            ->addSelect('a')
            ->andWhere('qa.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
        if (!$quizAttempt) {
            throw new \Exception('Quiz attempt does not exist.');
        }

        return $quizAttempt;
    }
}

<?php

namespace App\Service;

use App\Repository\QuizAttemptRepository;

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
        $quiz = $this->quizRepository->createQueryBuilder('qa')
            ->leftJoin('qa.quizAttemptQuestions', 'qaq')
            ->leftJoin('qaq.quizAttemptAnswers', 'qaa')
            ->leftJoin('qaa.answer', 'a')
            ->leftJoin('qaq.questions', 'q')
            ->addSelect('qaq')
            ->addSelect('qaa')
            ->addSelect('q')
            ->addSelect('a')
            ->andWhere('qa.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
        if (!$quiz) {
            throw new \Exception('Quiz attempt does not exist.');
        }

        return $quiz;
    }
}

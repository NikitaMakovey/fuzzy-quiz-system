<?php

namespace App\Service;

use App\Repository\QuizAttemptRepository;

class QuizAttemptService
{
    public function __construct(
        private readonly QuizAttemptRepository $quizAttemptRepository
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
        $quizAttempt = $this->quizAttemptRepository->findOneById($id);
        if (!$quizAttempt) {
            throw new \Exception('Quiz attempt does not exist.');
        }

        return $this->prepareQuizAttemptObject($quizAttempt);
    }

    private function prepareQuizAttemptObject(array $quizAttempt): array
    {
        $questions = array_map(function ($item) {
            return [
                'id' => $item['question']['id'],
                'text' => $item['question']['text'],
                'is_correct' => empty(array_filter($item['quizAttemptAnswers'], function ($answer) {
                    return !$answer['answer']['isCorrect'];
                })),
            ];
        }, $quizAttempt['quizAttemptQuestions']);

        usort($questions, fn ($left, $right) => $left['id'] - $right['id']);

        return [
            'id' => $quizAttempt['id'],
            'started_at' => $quizAttempt['startedAt'],
            'completed_at' => $quizAttempt['completedAt'],
            'correct_questions' => array_values(
                array_filter($questions, function ($item) {
                    return $item['is_correct'];
                })
            ),
            'incorrect_questions' => array_values(
                array_filter($questions, function ($item) {
                    return !$item['is_correct'];
                })
            ),
        ];
    }
}

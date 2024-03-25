<?php

namespace App\Service;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Query;

class QuestionService
{
    public function __construct(
        private readonly QuestionRepository $questionRepository
    )
    {
    }

    /**
     * @note
     * Get question by ID
     *
     * @param int $id
     * @return null|array
     * @throws \Exception
     */
    public function getQuestionById(int $id): ?array
    {
        $question = $this->questionRepository->createQueryBuilder('q')
            ->leftJoin('q.answers', 'a')
            ->addSelect('a')
            ->where('q.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult(Query::HYDRATE_ARRAY);

        if (!$question) {
            throw new \Exception('Question does not exist.');
        }

        return $this->prepareQuestionObject($question);
    }

    /**
     * @note
     * Prepare question object to return
     *
     * @param array $question
     * @return array
     */
    private function prepareQuestionObject(array $question): array
    {
        shuffle($question['answers']); // randomized order
        return [
            'id' => $question['id'],
            'text' => $question['text'],
            'answers' => array_map(function ($answer) {
                return [
                    'id' => $answer['id'],
                    'text' => $answer['text']
                ];
            }, $question['answers'] ?? [])
        ];
    }
}
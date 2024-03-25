<?php

namespace App\Controller;

use App\Service\QuizAttemptService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class QuizAttemptController extends AbstractController
{
    public function __construct(
        private readonly QuizAttemptService $quizAttemptService
    ) {
    }

    /**
     * @throws \Exception
     */
    #[Route('/quiz-attempts/{id}', name: 'quiz_attempt_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->json($this->quizAttemptService->getQuizAttemptById($id));
    }
}

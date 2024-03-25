<?php

namespace App\Controller;

use App\Service\QuizService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class QuizController extends AbstractController
{
    public function __construct(
        private readonly QuizService $quizService
    ) {
    }

    #[Route('/quizzes', name: 'quiz_list', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return $this->json($this->quizService->getAllQuizzes());
    }

    /**
     * @throws \Exception
     */
    #[Route('/quizzes/{id}', name: 'quiz_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->json($this->quizService->getQuizById($id));
    }

    /**
     * @throws \Exception
     */
    #[Route('/quizzes/{id}/start', name: 'quiz_start', requirements: ['id' => '\d+'], methods: ['POST', 'GET'])]
    public function start(int $id): JsonResponse
    {
        return $this->json(['next_question' => $this->quizService->startQuiz($id)]);
    }
}

<?php

namespace App\Controller;

use App\Service\QuestionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class QuestionController extends AbstractController
{
    public function __construct(
        private readonly QuestionService $questionService
    )
    {
    }

    /**
     * @throws \Exception
     */
    #[Route('/questions/{id}', name: 'question_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->json($this->questionService->getQuestionById($id));
    }
}

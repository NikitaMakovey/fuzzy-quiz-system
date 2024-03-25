<?php

namespace App\Controller;

use App\Request\AnswerQuestionRequest;
use App\Service\QuestionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class QuestionController extends AbstractController
{
    public function __construct(
        private readonly QuestionService $questionService
    ) {
    }

    /**
     * @throws \Exception
     */
    #[Route('/questions/{id}', name: 'question_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->json($this->questionService->getQuestionById($id));
    }

    /**
     * @throws \Exception
     */
    #[Route('/questions/{id}/answer', name: 'question_answer', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function answer(int $id, AnswerQuestionRequest $request): Response
    {
        $response = $this->questionService->answerQuestion($id, $request->answers);
        if (isset($response['next_question'])) {
            return $this->json($response);
        }

        return $this->redirectToRoute('api_quiz_attempt_show', ['id' => $response['quiz_attempt']]);
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\QuizService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class QuizController extends AbstractController
{
    public function __construct(
        private readonly QuizService $quizService,
        private readonly RequestStack $requestStack
    )
    {
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
        // check session
        $session = $this->requestStack->getSession();
        if (!$session->has(User::HASH_KEY)) {
            $session->set(User::HASH_KEY, uniqid());
        }
        $hash = $session->get(User::HASH_KEY);
        return $this->json(['next_question' => $this->quizService->startQuiz($id, $hash)]);
    }
}

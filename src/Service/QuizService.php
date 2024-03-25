<?php

namespace App\Service;

use App\Entity\Quiz;
use App\Entity\QuizAttempt;
use App\Entity\QuizAttemptQuestion;
use App\Entity\User;
use App\Repository\QuizRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class QuizService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly QuizRepository $quizRepository,
        private readonly UserRepository $userRepository,
        private readonly LoggerInterface $logger,
        private readonly RequestStack $requestStack
    ) {
    }

    /**
     * @note
     * Get all available quizzes
     *
     * @return array|Quiz[]
     */
    public function getAllQuizzes(): array
    {
        return $this->quizRepository->findAllRecords();
    }

    /**
     * @note
     * Get quiz by ID
     *
     * @throws \Exception
     */
    public function getQuizById(int $id): ?array
    {
        $quiz = $this->quizRepository->findOneById();
        if (!$quiz) {
            throw new \Exception('Quiz does not exist.');
        }

        return $quiz;
    }

    /**
     * @note
     * Start new quiz session using current user HASH as identification key
     *
     * @throws \Exception
     */
    public function startQuiz(int $id): ?int
    {
        $user = $this->getUser();

        $quiz = $this->quizRepository->find($id);

        $quizAttempt = $this->createQuizAttempt($user, $quiz);

        $firstQuestionId = $this->prepareQuizQuestions($quiz, $quizAttempt);

        // commit changes
        $this->entityManager->flush();

        return $firstQuestionId;
    }

    /**
     * @note
     * Get user if exists
     */
    private function getUser(): User
    {
        $hash = $this->checkSession();
        $user = $this->userRepository->findOneBy(['hash' => $hash]);
        if (!$user) {
            $this->logger->debug('User does not exist.', ['hash' => $hash]);
            $user = new User();
            $user->setHash($hash);
            $this->entityManager->persist($user);
        }

        return $user;
    }

    private function checkSession(): string
    {
        $session = $this->requestStack->getSession();
        if (!$session->has(User::HASH_KEY)) {
            $session->set(User::HASH_KEY, uniqid());
        }

        return $session->get(User::HASH_KEY);
    }

    /**
     * @note
     * Create new quiz attempt for current user
     */
    private function createQuizAttempt(User $user, Quiz $quiz): QuizAttempt
    {
        $quizAttempt = new QuizAttempt();
        $quizAttempt->setUser($user);
        $quizAttempt->setQuiz($quiz);
        $this->entityManager->persist($quizAttempt);

        return $quizAttempt;
    }

    private function prepareQuizQuestions(Quiz $quiz, QuizAttempt $quizAttempt): ?int
    {
        $firstQuestionId = null;
        $questions = $quiz->getQuestions();

        $questionsArray = $questions->toArray();
        shuffle($questionsArray);

        foreach ($questionsArray as $question) {
            if (!$firstQuestionId) {
                $firstQuestionId = $question->getId();
            }
            $quizAttemptQuestion = new QuizAttemptQuestion();
            $quizAttemptQuestion->setQuizAttempt($quizAttempt);
            $quizAttemptQuestion->setQuestion($question);
            $this->entityManager->persist($quizAttemptQuestion);
        }

        return $firstQuestionId; // first question to answer
    }
}

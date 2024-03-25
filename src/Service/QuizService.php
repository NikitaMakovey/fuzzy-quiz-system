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

class QuizService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly QuizRepository $quizRepository,
        private readonly UserRepository $userRepository,
        private readonly LoggerInterface $logger
    )
    {
    }

    /**
     * @note
     * Get all available quizzes
     *
     * @return array|Quiz[]
     */
    public function getAllQuizzes(): array
    {
        return $this->quizRepository->createQueryBuilder('quiz')
            ->select(['quiz.id', 'quiz.title'])
            ->getQuery()
            ->getResult();
    }

    /**
     * @note
     * Get quiz by ID
     *
     * @param int $id
     * @return null|array
     * @throws \Exception
     */
    public function getQuizById(int $id): ?array
    {
        $quiz = $this->quizRepository->createQueryBuilder('quiz')
            ->select(['quiz.id', 'quiz.title'])
            ->andWhere('quiz.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
        if (!$quiz) {
            throw new \Exception('Quiz does not exist.');
        }
        return $quiz;
    }

    /**
     * @note
     * Start new quiz session using current user HASH as identification key
     *
     * @param int $id
     * @param string $hash
     * @return null|int
     * @throws \Exception
     */
    public function startQuiz(int $id, string $hash): ?int
    {
        $user = $this->getUser($hash);

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
     *
     * @param string $hash
     * @return User
     */
    private function getUser(string $hash): User
    {
        $user = $this->userRepository->findOneBy(['hash' => $hash]);
        if (!$user) {
            $this->logger->debug('User does not exist.', ['hash' => $hash]);
            $user = new User();
            $user->setHash($hash);
            $this->entityManager->persist($user);
        }
        return $user;
    }

    /**
     * @note
     * Create new quiz attempt for current user
     *
     * @param User $user
     * @param Quiz $quiz
     * @return QuizAttempt
     */
    private function createQuizAttempt(User $user, Quiz $quiz): QuizAttempt
    {
        $quizAttempt = new QuizAttempt();
        $quizAttempt->setUser($user);
        $quizAttempt->setQuiz($quiz);
        $this->entityManager->persist($quizAttempt);
        return $quizAttempt;
    }

    /**
     * @param Quiz $quiz
     * @param QuizAttempt $quizAttempt
     * @return int|null
     */
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
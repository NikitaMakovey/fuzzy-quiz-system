<?php

namespace App\Service;

use App\Entity\Question;
use App\Entity\QuizAttempt;
use App\Entity\QuizAttemptAnswer;
use App\Entity\QuizAttemptQuestion;
use App\Entity\User;
use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use App\Repository\QuizAttemptQuestionRepository;
use App\Repository\QuizAttemptRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\RequestStack;

class QuestionService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly QuestionRepository $questionRepository,
        private readonly AnswerRepository $answerRepository,
        private readonly UserRepository $userRepository,
        private readonly QuizAttemptRepository $quizAttemptRepository,
        private readonly QuizAttemptQuestionRepository $quizAttemptQuestionRepository,
        private readonly RequestStack $requestStack
    ) {
    }

    /**
     * @note
     * Get question by ID
     *
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
                    'text' => $answer['text'],
                ];
            }, $question['answers'] ?? []),
        ];
    }

    /**
     * @throws \Exception
     */
    public function answerQuestion(int $id, array $answerIds): ?array
    {
        $user = $this->getUser();

        $question = $this->getQuestion($id);

        $quizAttempt = $this->getQuizAttempt($user);

        $quizAttemptQuestion = $this->getQuizAttemptQuestion($quizAttempt, $question);

        if ($this->isQuestionAnswered($quizAttemptQuestion)) {
            throw new \Exception('Question has been answered.');
        }

        $this->addAnswers($question, $quizAttemptQuestion, $answerIds);

        $nextQuestionId = $this->findNextUnansweredQuestionId($quizAttempt, $quizAttemptQuestion);
        if ($nextQuestionId) {
            return ['next_question' => $nextQuestionId];
        }

        // quiz attempt successfully complete
        $quizAttempt->setCompletedAt(new \DateTimeImmutable());
        $this->entityManager->persist($quizAttempt);
        $this->entityManager->flush();

        return ['quiz_attempt' => $quizAttempt->getId()];
    }

    private function isQuestionAnswered(QuizAttemptQuestion $quizAttemptQuestion): bool
    {
        $answers = $quizAttemptQuestion->getQuizAttemptAnswers();

        return !$answers->isEmpty();
    }

    private function findNextUnansweredQuestionId(QuizAttempt $quizAttempt, QuizAttemptQuestion $currentQuestion): ?int
    {
        $questions = $quizAttempt->getQuizAttemptQuestions();

        foreach ($questions as $question) {
            if ($question == $currentQuestion) {
                continue;
            }

            $answers = $question->getQuizAttemptAnswers();

            if ($answers->isEmpty()) {
                // this question has not been answered yet
                return $question->getQuestion()->getId();
            }
        }

        // all questions have been answered
        return null;
    }

    /**
     * @throws \Exception
     */
    private function getQuestion(int $id): Question
    {
        $question = $this->questionRepository->find($id);
        if (!$question) {
            throw new \Exception('Question not found.');
        }

        return $question;
    }

    /**
     * @throws \Exception
     */
    private function getQuizAttempt(User $user): QuizAttempt
    {
        $quizAttempt = $this->quizAttemptRepository->findOneBy(['user' => $user], ['id' => 'DESC']);
        if (!$quizAttempt) {
            throw new \Exception('Quiz Attempt not found.');
        }

        return $quizAttempt;
    }

    /**
     * @throws \Exception
     */
    private function getQuizAttemptQuestion(QuizAttempt $quizAttempt, Question $question): QuizAttemptQuestion
    {
        $quizAttemptQuestion = $this->quizAttemptQuestionRepository->findOneBy([
            'quizAttempt' => $quizAttempt,
            'question' => $question,
        ]);
        if (!$quizAttemptQuestion) {
            throw new \Exception('Quiz Attempt Question not found.');
        }

        return $quizAttemptQuestion;
    }

    /**
     * @throws \Exception
     */
    private function addAnswers(Question $question, QuizAttemptQuestion $quizAttemptQuestion, array $answerIds): void
    {
        foreach ($answerIds as $answerId) {
            $answer = $this->answerRepository->find($answerId);

            if (!$answer) {
                throw new \Exception("Answer with ID $answerId not found.");
            }

            if ($answer->getQuestion() !== $question) {
                throw new \Exception("Answer with ID {$answerId} does not belong to the specified question.");
            }

            $quizAttemptAnswer = new QuizAttemptAnswer();
            $quizAttemptAnswer->setQuizAttemptQuestion($quizAttemptQuestion);
            $quizAttemptAnswer->setAnswer($answer);
            $this->entityManager->persist($quizAttemptAnswer);
        }

        $this->entityManager->flush();
    }

    /**
     * @note
     * Get user from session
     *
     * @throws \Exception
     */
    private function getUser(): ?User
    {
        $session = $this->requestStack->getSession();
        if (!$session->has(User::HASH_KEY)) {
            // @todo: redirect to homepage or throw error
            throw new \Exception('User does not exist.');
        }
        $hash = $session->get(User::HASH_KEY);
        $user = $this->userRepository->findOneBy(['hash' => $hash]);
        if (!$user) {
            throw new \Exception('User does not exist.');
        }

        return $user;
    }
}

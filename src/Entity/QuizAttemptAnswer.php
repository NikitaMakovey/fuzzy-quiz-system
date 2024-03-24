<?php

namespace App\Entity;

use App\Repository\QuizAttemptAnswerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizAttemptAnswerRepository::class)]
class QuizAttemptAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(targetEntity: QuizAttemptQuestion::class, inversedBy: 'quizAttemptAnswers')]
    #[ORM\JoinColumn(nullable: false)]
    private QuizAttemptQuestion $quizAttemptQuestion;

    #[ORM\ManyToOne(targetEntity: Answer::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Answer $answer;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getQuizAttemptQuestion(): ?QuizAttemptQuestion
    {
        return $this->quizAttemptQuestion;
    }

    public function setQuizAttemptQuestion(?QuizAttemptQuestion $quizAttemptQuestion): self
    {
        $this->quizAttemptQuestion = $quizAttemptQuestion;

        return $this;
    }

    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }

    public function setAnswer(?Answer $answer): self
    {
        $this->answer = $answer;

        return $this;
    }
}

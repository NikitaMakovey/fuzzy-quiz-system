<?php

namespace App\Entity;

use App\Repository\QuizAttemptQuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizAttemptQuestionRepository::class)]
class QuizAttemptQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(targetEntity: Question::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Question $question;

    #[ORM\ManyToOne(targetEntity: QuizAttempt::class, inversedBy: 'quizAttemptQuestions')]
    #[ORM\JoinColumn(nullable: false)]
    private QuizAttempt $quizAttempt;

    /**
     * @var Collection|QuizAttemptAnswer[]
     */
    #[ORM\OneToMany(targetEntity: QuizAttemptAnswer::class, mappedBy: 'quizAttemptQuestion', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $quizAttemptAnswers;

    public function __construct()
    {
        $this->quizAttemptAnswers = new ArrayCollection();
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

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getQuizAttempt(): ?QuizAttempt
    {
        return $this->quizAttempt;
    }

    public function setQuizAttempt(?QuizAttempt $quizAttempt): self
    {
        $this->quizAttempt = $quizAttempt;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getQuizAttemptAnswers(): Collection
    {
        return $this->quizAttemptAnswers;
    }

    public function addQuizAttemptAnswer(QuizAttemptAnswer $quizAttemptAnswer): self
    {
        if (!$this->quizAttemptAnswers->contains($quizAttemptAnswer)) {
            $this->quizAttemptAnswers[] = $quizAttemptAnswer;
            $quizAttemptAnswer->setQuizAttemptQuestion($this);
        }

        return $this;
    }
}

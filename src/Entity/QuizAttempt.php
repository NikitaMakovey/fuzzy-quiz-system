<?php

namespace App\Entity;

use App\Repository\QuizAttemptRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizAttemptRepository::class)]
class QuizAttempt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private \DateTimeImmutable $startedAt;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $completedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'quizAttempts')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Quiz::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Quiz $quiz;

    /**
     * @var Collection|QuizAttemptQuestion[]
     */
    #[ORM\OneToMany(targetEntity: QuizAttemptQuestion::class, mappedBy: 'quizAttempt', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $quizAttemptQuestions;

    public function __construct()
    {
        $this->quizAttemptQuestions = new ArrayCollection();
        $this->startedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): static
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getCompletedAt(): ?\DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?\DateTimeImmutable $completedAt): static
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * @return Collection|QuizAttemptQuestion[]
     */
    public function getQuizAttemptQuestions(): Collection
    {
        return $this->quizAttemptQuestions;
    }

    public function addQuizAttemptQuestion(QuizAttemptQuestion $quizAttemptQuestion): self
    {
        if (!$this->quizAttemptQuestions->contains($quizAttemptQuestion)) {
            $this->quizAttemptQuestions[] = $quizAttemptQuestion;
            $quizAttemptQuestion->setQuizAttempt($this);
        }

        return $this;
    }
}

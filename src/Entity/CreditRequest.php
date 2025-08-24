<?php

namespace App\Entity;

use App\Repository\CreditRequestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CreditRequestRepository::class)]
class CreditRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private Car $car;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private CreditProgram $program;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private string $initialPayment;

    #[ORM\Column]
    private int $loanTerm;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }

    public function getCar(): Car { return $this->car; }
    public function setCar(Car $car): self { $this->car = $car; return $this; }

    public function getProgram(): CreditProgram { return $this->program; }
    public function setProgram(CreditProgram $program): self { $this->program = $program; return $this; }

    public function getInitialPayment(): string { return $this->initialPayment; }
    public function setInitialPayment(string $v): self { $this->initialPayment = $v; return $this; }

    public function getLoanTerm(): int { return $this->loanTerm; }
    public function setLoanTerm(int $v): self { $this->loanTerm = $v; return $this; }

    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
}

<?php

namespace App\Entity;

use App\Repository\CreditProgramRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CreditProgramRepository::class)]
class CreditProgram
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $title;

    #[ORM\Column(type: 'float')]
    private float $interestRate;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2, nullable: true)]
    private ?string $minInitialPayment = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $maxMonthlyPayment = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $maxLoanTermMonths = null;

    public function getId(): ?int { return $this->id; }
    public function getTitle(): string { return $this->title; }
    public function setTitle(string $title): self { $this->title = $title; return $this; }

    public function getInterestRate(): float { return $this->interestRate; }
    public function setInterestRate(float $rate): self { $this->interestRate = $rate; return $this; }

    public function getMinInitialPayment(): ?string { return $this->minInitialPayment; }
    public function setMinInitialPayment(?string $v): self { $this->minInitialPayment = $v; return $this; }

    public function getMaxMonthlyPayment(): ?int { return $this->maxMonthlyPayment; }
    public function setMaxMonthlyPayment(?int $v): self { $this->maxMonthlyPayment = $v; return $this; }

    public function getMaxLoanTermMonths(): ?int { return $this->maxLoanTermMonths; }
    public function setMaxLoanTermMonths(?int $v): self { $this->maxLoanTermMonths = $v; return $this; }
}

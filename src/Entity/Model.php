<?php

namespace App\Entity;

use App\Repository\ModelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ModelRepository::class)]
class Model
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['car:detail'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['car:detail'])]
    private string $name;

    #[ORM\ManyToOne(inversedBy: 'models')]
    #[ORM\JoinColumn(nullable: false)]
    private Brand $brand;

    /** @var Collection<int, Car> */
    #[ORM\OneToMany(mappedBy: 'model', targetEntity: Car::class)]
    private Collection $cars;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    public function getBrand(): Brand { return $this->brand; }
    public function setBrand(Brand $brand): self { $this->brand = $brand; return $this; }

    /** @return Collection<int, Car> */
    public function getCars(): Collection { return $this->cars; }
}

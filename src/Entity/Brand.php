<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BrandRepository::class)]
class Brand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['car:list', 'car:detail'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['car:list', 'car:detail'])]
    private string $name;

    /** @var Collection<int, Car> */
    #[ORM\OneToMany(mappedBy: 'brand', targetEntity: Car::class)]
    private Collection $cars;

    /** @var Collection<int, Model> */
    #[ORM\OneToMany(mappedBy: 'brand', targetEntity: Model::class, cascade: ['persist'])]
    private Collection $models;

    public function __construct()
    {
        $this->cars = new ArrayCollection();
        $this->models = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }

    /** @return Collection<int, Car> */
    public function getCars(): Collection { return $this->cars; }

    /** @return Collection<int, Model> */
    public function getModels(): Collection { return $this->models; }
}

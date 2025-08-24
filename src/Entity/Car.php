<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['car:list', 'car:detail'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['car:list', 'car:detail'])]
    private Brand $brand;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['car:detail'])]
    private Model $model;

    #[ORM\Column(length: 255)]
    #[Groups(['car:list', 'car:detail'])]
    private string $photo;

    #[ORM\Column]
    #[Groups(['car:list', 'car:detail'])]
    private int $price;

    public function getId(): ?int { return $this->id; }
    public function getBrand(): Brand { return $this->brand; }
    public function setBrand(Brand $brand): self { $this->brand = $brand; return $this; }

    public function getModel(): Model { return $this->model; }
    public function setModel(Model $model): self { $this->model = $model; return $this; }

    public function getPhoto(): string { return $this->photo; }
    public function setPhoto(string $photo): self { $this->photo = $photo; return $this; }

    public function getPrice(): int { return $this->price; }
    public function setPrice(int $price): self { $this->price = $price; return $this; }
}

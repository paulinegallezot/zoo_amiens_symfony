<?php

namespace App\Entity;

use App\Entity\Traits\DateTrait;
use App\Entity\Traits\UuidTrait;
use App\Repository\AnimalFoodRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AnimalFoodRepository::class)]
#[ORM\HasLifecycleCallbacks]
class AnimalFood
{
    use UuidTrait;
    use DateTrait;

    #[ORM\ManyToOne(targetEntity: Animal::class, inversedBy: 'animalFoods')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['default'])]
    private Animal $animal;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'animalFoods')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['default'])]
    private User $user;


    #[ORM\ManyToOne(targetEntity: Food::class, inversedBy: 'animalFoods')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['default'])]
    private Food $food;

    #[ORM\Column(type: 'integer')]
    #[Groups(['default'])]
    private int $quantityInGrams;


    #[ORM\Column(type: "datetime_immutable", nullable: true)]
    #[Groups(['default'])]
    private ?DateTimeImmutable $setAt = null;

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): self
    {
        $this->animal = $animal;
        return $this;
    }

    public function getFood(): ?Food
    {
        return $this->food;
    }

    public function setFood(?Food $food): self
    {
        $this->food = $food;
        return $this;
    }

    public function getQuantityInGrams(): ?int
    {
        return $this->quantityInGrams;
    }

    public function setQuantityInGrams(int $quantityInGrams): self
    {
        $this->quantityInGrams = $quantityInGrams;
        return $this;
    }
    public function getUser(): User
    {
        return $this->user;
    }
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getSetAt(): ?DateTimeImmutable
    {
        return $this->setAt;
    }

    public function setSetAt(?DateTimeImmutable $setAt): void
    {
        $this->setAt = $setAt;
    }

    #[ORM\PrePersist]
    public function initializeSetAt(): void
    {
        if ($this->setAt === null) {
            $this->setAt = new DateTimeImmutable();
        }
    }


}


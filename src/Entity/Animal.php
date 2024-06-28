<?php

namespace App\Entity;

use AllowDynamicProperties;

use App\Entity\Race;
use App\Entity\Traits\DateTrait;
use App\Entity\Traits\UuidTrait;
use App\Repository\AnimalRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[AllowDynamicProperties] #[ORM\Entity(repositoryClass: AnimalRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity('name', message: 'Ce nom est déjà utilisé. Veuillez en choisir un autre.')]
class Animal
{
    use UuidTrait;
    use DateTrait;

    #[ORM\Column(length: 100, unique: true)]
    #[Groups(['default'])]
    private ?string $name = null;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $slug = null;


    #[ORM\ManyToOne(inversedBy: 'animals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?race $race = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Habitat $habitat = null;


    #[ORM\OneToMany(mappedBy: 'animal', targetEntity: AnimalImage::class, fetch: 'EAGER', cascade: ['persist', 'remove'])]
    #[Groups(['default'])]
    private Collection $images;







    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }
    public function getSlug(): ?string
    {
        return $this->slug;
    }
    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }
    #[Groups(['default'])]
    public function getRace(): ?race
    {
        return $this->race;
    }
    public function setRace(?race $race): static
    {
        $this->race = $race;
        return $this;
    }
    #[Groups(['default'])]
    public function getHabitat(): ?Habitat
    {
        return $this->habitat;
    }

    public function setHabitat(?Habitat $habitat): static
    {
        $this->habitat = $habitat;

        return $this;
    }



    public function __construct()
    {
        $this->images = new ArrayCollection();

    }
    // Getters and setters for other properties
    #[Groups(['default2'])]
    public function getImages(): Collection
    {
        return $this->images;
    }
    public function addImage(AnimalImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setAnimal($this);
        }
        return $this;
    }





}

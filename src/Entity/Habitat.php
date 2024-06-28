<?php

namespace App\Entity;




use App\Entity\Traits\DateTrait;
use App\Entity\Traits\UuidTrait;
use App\Repository\HabitatRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: HabitatRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Habitat
{
    use UuidTrait;
    use DateTrait;

    #[ORM\Column(length: 100)]
    #[Groups(['default'])]
    private ?string $name = null;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['default'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::INTEGER, nullable: false,options: ['default' => 0])]
    #[Groups(['default'])]
    private ?int $counterAnimal  = 0;

    #[ORM\OneToMany(targetEntity: HabitatImage::class, mappedBy: 'habitat', cascade: ['persist', 'remove'], fetch: 'EAGER')]
    #[Groups(['default'])]
    private Collection $images;



    /**
     * @var Collection<int, animal>
     */
    #[ORM\OneToMany(targetEntity: Animal::class, mappedBy: 'habitat')]
    private Collection $animals;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->animals = new ArrayCollection();
    }



    public function getId(): ?UuidInterface
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

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

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, animal>
     */
    public function getAnimals(): Collection
    {
        return $this->animals;
    }

    public function addAnimal(animal $animal): static
    {
        if (!$this->animals->contains($animal)) {
            $this->animals->add($animal);
            $animal->setHabitat($this);
        }

        return $this;
    }

    public function removeAnimal(animal $animal): static
    {
        if ($this->animals->removeElement($animal)) {
            // set the owning side to null (unless already changed)
            if ($animal->getHabitat() === $this) {
                $animal->setHabitat(null);
            }
        }

        return $this;
    }


    #[Groups(['default2'])]
    public function getImages(): Collection
    {
        return $this->images;
    }
    public function addImage(HabitatImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setHabitat($this);
        }
        return $this;
    }

    public function getCounterAnimal(){
        return $this->counterAnimal;
    }
    public function setCounterAnimal($counter)
    {
        $this->counterAnimal = $counter;
    }
    public function incrementCounterAnimal()
    {

        $this->counterAnimal++;
    }
    public function decrementCounterAnimal()
    {
        if ($this->counterAnimal > 0) {
            $this->counterAnimal--;
        }
    }


}

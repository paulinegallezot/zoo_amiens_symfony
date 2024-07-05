<?php

namespace App\Entity;

use App\Entity\Traits\DateTrait;
use App\Entity\Traits\UuidTrait;
use App\Repository\FoodRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FoodRepository::class)]

#[ORM\HasLifecycleCallbacks]
class Food
{
    use UuidTrait;
    use DateTrait;

    #[Groups(['default'])]
    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT,nullable:true)]
    #[Groups(['default'])]
    private ?string $description = null;

   /* #[ORM\OneToMany(targetEntity: ReportFood::class, mappedBy: 'food', cascade: ['persist', 'remove'])]
    private $reportFoods;*/


    public function __construct()
    {
        //$this->reportFoods = new ArrayCollection();
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
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
    public function getReportFoods(): ArrayCollection
    {
        return $this->reportFoods;
    }
    public function addReportFood(ReportFood $reportFood): self
    {
        if (!$this->reportFoods->contains($reportFood)) {
            $this->reportFoods[] = $reportFood;
            $reportFood->setFood($this);
        }

        return $this;
    }

    public function removeReportFood(ReportFood $reportFood): self
    {
        if ($this->reportFoods->removeElement($reportFood)) {
            if ($reportFood->getFood() === $this) {
                $reportFood->setFood(null);
            }
        }

        return $this;
    }

}

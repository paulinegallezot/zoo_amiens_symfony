<?php
namespace App\Entity;

use App\Entity\Traits\DateTrait;
use App\Entity\Traits\UuidTrait;
use App\Repository\HabitatReportRepository;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
#[ORM\Entity(repositoryClass:HabitatReportRepository::class)]
#[ORM\HasLifecycleCallbacks]
class HabitatReport
{
    use UuidTrait;
    use DateTrait;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['default'])]
    private ?string $review = null;

    #[ORM\ManyToOne(inversedBy: 'habitatReports')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['default'])]
    private ?Habitat $habitat = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'habitatReports')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['default'])]
    private User $user;

    public function getReview(): ?string
    {
        return $this->review;
    }

    public function setReview(?string $review): static
    {
        $this->review = $review;

        return $this;
    }

    public function getHabitat(): ?Habitat
    {
        return $this->habitat;
    }

    public function setHabitat(?Habitat $habitat): static
    {
        $this->habitat = $habitat;

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
}


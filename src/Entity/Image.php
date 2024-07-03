<?php

namespace App\Entity;

use App\Entity\Traits\DateTrait;
use App\Entity\Traits\UuidTrait;
use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "entity_type", type: "string")]
#[ORM\DiscriminatorMap([
  "animal" => "AnimalImage",
  "habitat" => "HabitatImage"
])]
/*#[ORM\EntityListeners(["App\EventListener\ImageDeleteListener"])] impossible car on doit passer $imageDirectory*/
abstract class Image
{
    use UuidTrait;
    use DateTrait;


    #[ORM\Column(length: 100)]

    private ?string $filename = null;

    #[ORM\Column(length: 5)]

    private ?string $extension = null;


    #[ORM\Column(type: 'uuid',nullable: true)]
    private ?UuidInterface $entityId = null;

    public function getEntityId(): ?UuidInterface
    {
        return $this->entityId;
    }

    public function setEntityId(string $entityId): self
    {

        $this->entityId = Uuid::fromString($entityId);
        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): static
    {
        $this->extension = $extension;

        return $this;
    }


    #[Groups(['default'])]
    public function getThumbnail(): ?string
    {
        return $this->filename.'-thumb.'.$this->extension;
    }

    public function getFilenameWithExtension($postfix = ''): ?string{
            if ($postfix) $postfix='-'.$postfix;
            return $this->filename.$postfix.'.'.$this->extension;
    }




}

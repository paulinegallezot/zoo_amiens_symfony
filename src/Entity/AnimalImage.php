<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class AnimalImage extends Image
{
    #[ORM\ManyToOne(targetEntity: Animal::class, inversedBy: 'images')]
    #[ORM\JoinColumn(name: "entity_id", referencedColumnName: "id", nullable: false)]
    private ?Animal $animal;

    public function setAnimal(?Animal $animal): self
    {
        $this->animal = $animal;
        return $this;
    }

}

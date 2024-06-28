<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class HabitatImage extends Image
{
    #[ORM\ManyToOne(targetEntity: Habitat::class, inversedBy: 'images')]
    #[ORM\JoinColumn(name: "entity_id", referencedColumnName: "id", nullable: false)]
    private ?Habitat $habitat;

    public function setHabitat(?Habitat $habitat): self
    {
        $this->habitat = $habitat;
        return $this;
    }

}

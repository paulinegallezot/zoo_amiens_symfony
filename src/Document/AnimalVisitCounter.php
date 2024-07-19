<?php

namespace App\Document;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\AnimalVisitCounterIncrementController;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use ApiPlatform\Metadata\Post;

#[MongoDB\Document(collection: "animal_visit_counters")]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/increment-visit/{animalName}',
            controller: AnimalVisitCounterIncrementController::class,
            openapiContext: [
                'summary' => 'Increments the visit counter for a given animal',
                'parameters' => [
                    [
                        'name' => 'animalName',
                        'in' => 'path',
                        'required' => true,
                        'schema' => [
                            'type' => 'string'
                        ]
                    ]
                ]
            ],
            read: false,
            deserialize: false,
        )
    ]
)]
class AnimalVisitCounter
{
    #[MongoDB\Id]
    private $id;

    #[MongoDB\Field(type: "string")]
    private $animalName;

    #[MongoDB\Field(type: "int")]
    private $visitCount = 0;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getAnimalName(): ?string
    {
        return $this->animalName;
    }

    public function setAnimalName(string $animalName): self
    {
        $this->animalName = $animalName;
        return $this;
    }

    public function getVisitCount(): ?int
    {
        return $this->visitCount;
    }

    public function incrementVisitCount(): self
    {
        $this->visitCount++;
        return $this;
    }
}


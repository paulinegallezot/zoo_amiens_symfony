<?php
namespace App\Controller;

use App\Service\AnimalVisitCounterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AnimalVisitCounterIncrementController extends AbstractController
{
    private $animalVisitCounterService;

    public function __construct(AnimalVisitCounterService $animalVisitCounterService)
    {
        $this->animalVisitCounterService = $animalVisitCounterService;
    }

    public function __invoke(string $animalName): JsonResponse
    {
        try {
            $counter = $this->animalVisitCounterService->incrementVisitCount($animalName);

            return $this->json([
                'animalName' => $counter->getAnimalName(),
                'visitCount' => $counter->getVisitCount(),
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'error' => $e->getMessage(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

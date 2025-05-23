<?php

namespace App\Controller\Admin;

use App\Controller\Bootstrap\AdminLayoutController;
use App\Service\AnimalVisitCounterService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AdminLayoutController
{
    protected string $entityTitle = 'Dashboard';

    #[Route('/', name: 'index')]
    public function die( ): Response
    {

        return new Response("What are you doing here?");

    }
    #[Route('/admin', name: 'app_admin_index')]
    public function index( Security $security,AnimalVisitCounterService $animalVisitCounterService): Response
    {
        $user = $security->getUser();
        $roles = $user ? $user->getRoles() : [];
        $view=strtolower('admin/dashboard/'.$roles[0].'.html.twig');

        $counters = $animalVisitCounterService->finAllAnimalCounters();


        return $this->render($view, [

            'page_title' => "Administration",
            'counters' => $counters,
        ]);
    }
}

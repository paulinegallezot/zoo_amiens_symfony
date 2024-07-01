<?php

namespace App\Controller;

use App\Controller\Bootstrap\AdminLayoutController;
/*use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;*/
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AdminLayoutController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'page_title' => "Administration page!!",
        ]);
    }
}

<?php

namespace App\Controller\Admin;

use App\Controller\Bootstrap\AdminLayoutController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
class AdminController extends AdminLayoutController
{
    protected string $entityTitle = 'Dashboard';


    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'page_title' => "Administration page!!",
        ]);
    }
}

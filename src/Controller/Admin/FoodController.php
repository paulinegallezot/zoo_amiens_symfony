<?php

namespace App\Controller\Admin;
use App\Controller\Bootstrap\AdminLayoutController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
#[IsGranted('ROLE_ADMIN')]
class FoodController extends AdminLayoutController
{

    protected string $entityName = 'Food';
    protected string $entityTitle = 'Aliment';
    protected string $gender = 'f';
    protected string $render = 'global';


    #[Route('/admin/food', name: 'app_admin_food')]
    public function index(): Response
    {
        return parent::indexCRUD();
    }

    #[Route('/admin/food/new', name: 'app_admin_food_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        return parent::newCRUD(  $request);
    }

    #[Route('/admin/food/edit/{id}', name: 'app_admin_food_edit', methods: ['GET', 'POST'])]
    public function edit(string $id, Request $request,): Response
    {
        return $this->editCRUD($id, $request);
    }

    #[Route('/admin/food/ajax_delete', name: 'ajax_food_delete', methods: ['DELETE'])]
    public function ajaxDelete(Request $request): Response
    {
        return $this->ajaxDeleteCRUD($request,'Animal');
    }
    
    #[Route('/admin/food/ajax_datatable', name: 'ajax_food_datatable')]
    public function ajax_datatable(Request $request): Response
    {
        return $this->ajaxDatatableCRUD($request);
    }



}

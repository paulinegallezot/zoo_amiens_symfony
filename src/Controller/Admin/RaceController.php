<?php

namespace App\Controller\Admin;
use App\Controller\Bootstrap\AdminLayoutController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RaceController extends AdminLayoutController
{

    protected string $entityName = 'Race';
    protected string $gender = 'f';
    protected string $render = 'global';


    #[Route('/admin/race', name: 'app_admin_race')]
    public function index(): Response
    {
        return parent::indexCRUD();
    }

    #[Route('/admin/race/new', name: 'app_admin_race_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        return parent::newCRUD(  $request);
    }

    #[Route('/admin/race/edit/{id}', name: 'app_admin_race_edit', methods: ['GET', 'POST'])]
    public function edit(string $id, Request $request,): Response
    {
        return $this->editCRUD($id, $request);
    }

    #[Route('/admin/race/ajax_delete', name: 'ajax_race_delete', methods: ['DELETE'])]
    public function ajaxDelete(Request $request): Response
    {
        return $this->ajaxDeleteCRUD($request,'Animal');
    }
    
    #[Route('/admin/race/ajax_datatable', name: 'ajax_race_datatable')]
    public function ajax_datatable(Request $request): Response
    {
        return $this->ajaxDatatableCRUD($request);
    }



}

<?php

namespace App\Controller\Admin;
use App\Controller\Bootstrap\AdminLayoutController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class HealthStatusController extends AdminLayoutController
{

    protected string $entityName = 'HealthStatus';
    protected string $entityTitle = 'Etats de santés';
    protected string $entityTitleSingular = 'Etat de santé';
    protected string $gender = 'm';
    protected string $render = 'global';


    #[Route('/admin/health_status', name: 'app_admin_health_status')]
    public function index(): Response
    {
        return parent::indexCRUD();
    }

    #[Route('/admin/health_status/new', name: 'app_admin_health_status_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        return parent::newCRUD(  $request);
    }

    #[Route('/admin/health_status/edit/{id}', name: 'app_admin_health_status_edit', methods: ['GET', 'POST'])]
    public function edit(string $id, Request $request,): Response
    {
        return $this->editCRUD($id, $request);
    }

    #[Route('/admin/health_status/ajax_delete', name: 'ajax_health_status_delete', methods: ['DELETE'])]
    public function ajaxDelete(Request $request): Response
    {
        return $this->ajaxDeleteCRUD($request,'Animal');
    }
    
    #[Route('/admin/health_status/ajax_datatable', name: 'ajax_health_status_datatable')]
    public function ajax_datatable(Request $request): Response
    {
        return $this->ajaxDatatableCRUD($request);
    }



}

<?php

namespace App\Controller\Admin;
use App\Controller\Bootstrap\AdminLayoutController;
use App\Security\RoleExpressions;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(new Expression(RoleExpressions::ADMIN_OR_EMPLOYE))]
class ServiceController extends AdminLayoutController
{

    protected string $entityName = 'Service';
    protected string $entityTitle = 'Services';
    protected string $entityTitleSingular = 'Service';
    protected string $gender = 'm';
    protected string $render = 'global';


    #[Route('/admin/service', name: 'app_admin_service_index')]
    public function index(): Response
    {
        return parent::indexCRUD();
    }

    #[Route('/admin/service/new', name: 'app_admin_service_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request): Response
    {
        return parent::newCRUD(  $request);
    }

    #[Route('/admin/service/edit/{id}', name: 'app_admin_service_edit', methods: ['GET', 'POST'])]
    public function edit(string $id, Request $request,): Response
    {
        return $this->editCRUD($id, $request);
    }

    #[Route('/admin/service/ajax_delete', name: 'ajax_service_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function ajaxDelete(Request $request): Response
    {
        return $this->ajaxDeleteCRUD($request,'Animal');
    }
    
    #[Route('/admin/service/ajax_datatable', name: 'ajax_service_datatable')]
    public function ajax_datatable(Request $request): Response
    {
        return $this->ajaxDatatableCRUD($request);
    }



}

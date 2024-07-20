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
class ReviewController extends AdminLayoutController
{

    protected string $entityName = 'Review';
    protected string $entityTitle = 'Avis';
    protected string $gender = 'm';
    protected string $render = 'global';


    #[Route('/admin/review', name: 'app_admin_review_index')]
    public function index(): Response
    {
        $this->theme->addJavascriptFile('https://npmcdn.com/flatpickr@4.6.13/dist/l10n/fr.js');
        $this->theme->addJavascriptFile('js/dateRanges.js');


        return parent::indexCRUD();
    }

    #[Route('/admin/review/new', name: 'app_admin_review_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request): Response
    {
        return parent::newCRUD(  $request);
    }

    #[Route('/admin/review/edit/{id}', name: 'app_admin_review_edit', methods: ['GET', 'POST'])]
    public function edit(string $id, Request $request,): Response
    {
        return $this->editCRUD($id, $request);
    }

    #[Route('/admin/review/ajax_delete', name: 'ajax_review_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function ajaxDelete(Request $request): Response
    {
        return $this->ajaxDeleteCRUD($request,'Animal');
    }
    
    #[Route('/admin/review/ajax_datatable', name: 'ajax_review_datatable')]
    public function ajax_datatable(Request $request): Response
    {
        return $this->ajaxDatatableCRUD($request);
    }



}

<?php

namespace App\Controller\Admin;
use App\Controller\Bootstrap\AdminLayoutController;
use App\Entity\HabitatReport;
use App\Repository\HabitatRepository;
use App\Repository\UserRepository;
use App\Security\RoleExpressions;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class HabitatReportController extends AdminLayoutController
{

    protected string $entityName = 'HabitatReport';
    protected string $entityTitle = 'Rapports sur les habitats';
    protected string $entityTitleSingular = 'Rapport sur l\'habitat';
    protected string $gender = 'm';
    protected string $render = 'global';


    #[Route('/admin/habitat_report', name: 'app_admin_habitat_report_index')]
    #[IsGranted(new Expression(RoleExpressions::ADMIN_OR_VETO))]
    public function index(HabitatRepository $habitatRepository,UserRepository $userRepository): Response
    {
        $this->theme->addJavascriptFile('https://npmcdn.com/flatpickr@4.6.13/dist/l10n/fr.js');
        $this->theme->addJavascriptFile('js/dateRanges.js');

        $habitats = $habitatRepository->findBy([], ['name' => 'ASC']);
        $users = $userRepository->findByRole('ROLE_VETO');

        $additionalParams = [
            'habitats' => $habitats,
            'users' => $users,
        ];

        return parent::indexCRUD($additionalParams);
    }

    #[Route('/admin/habitat_report/new', name: 'app_admin_habitat_report_new', methods: ['GET', 'POST'])]
    #[IsGranted(new Expression(RoleExpressions::VETO))]
    public function new(Request $request): Response
    {
        return parent::newCRUD(  $request);
    }

    #[Route('/admin/habitat_report/edit/{id}', name: 'app_admin_habitat_report_edit', methods: ['GET', 'POST'])]
    #[IsGranted('edit', subject: 'habitatReport')]
    public function edit(string $id, Request $request,HabitatReport $habitatReport): Response
    {
        return $this->editCRUD($id, $request);
    }

    // on modifie la route pour ajouter le paramètre id pour que le Voter fonctionne correctement !!
    #[Route('/admin/habitat_report/ajax_delete/{id}', name: 'ajax_habitat_report_delete', methods: ['POST'])]
    #[IsGranted('ajaxDelete', subject: 'habitatReport')]
    public function ajaxDelete(Request $request,HabitatReport $habitatReport): Response
    {
        return $this->ajaxDeleteCRUD($request);
    }
    
    #[Route('/admin/habitat_report/ajax_datatable', name: 'ajax_habitat_report_datatable')]
    #[IsGranted(new Expression(RoleExpressions::ADMIN_OR_VETO))]
    public function ajax_datatable(Request $request): Response
    {
        return $this->ajaxDatatableCRUD($request);
    }

}

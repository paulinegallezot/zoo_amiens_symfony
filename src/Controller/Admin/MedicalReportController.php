<?php

namespace App\Controller\Admin;
use App\Controller\Bootstrap\AdminLayoutController;
use App\Entity\MedicalReport;
use App\Repository\AnimalRepository;
use App\Repository\UserRepository;
use App\Security\RoleExpressions;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class MedicalReportController extends AdminLayoutController
{

    protected string $entityName = 'MedicalReport';
    protected string $entityTitle = 'Rapports médicaux';
    protected string $entityTitleSingular = 'Rapport médical';
    protected string $gender = 'm';
    protected string $render = 'global';


    #[Route('/admin/medical_report', name: 'app_admin_medical_report_index')]
    #[IsGranted(new Expression(RoleExpressions::ALL))]
    public function index(AnimalRepository $animalRepository,UserRepository $userRepository): Response
    {
        $this->theme->addJavascriptFile('https://npmcdn.com/flatpickr@4.6.13/dist/l10n/fr.js');
        $this->theme->addJavascriptFile('js/dateRanges.js');

        $animals = $animalRepository->findBy([], ['name' => 'ASC']);
        $users = $userRepository->findByRole('ROLE_VETO');

        $additionalParams = [
            'animals' => $animals,
            'users' => $users,
        ];

        return parent::indexCRUD($additionalParams);
    }

    #[Route('/admin/medical_report/new', name: 'app_admin_medical_report_new', methods: ['GET', 'POST'])]
    #[IsGranted(new Expression(RoleExpressions::VETO))]
    public function new(Request $request): Response
    {
        return parent::newCRUD(  $request);
    }

    #[Route('/admin/medical_report/edit/{id}', name: 'app_admin_medical_report_edit', methods: ['GET', 'POST'])]
    #[IsGranted('edit', subject: 'medicalReport')]
    public function edit(string $id, Request $request,MedicalReport $medicalReport): Response
    {
        return $this->editCRUD($id, $request);
    }

    // on modifie la route pour ajouter le paramètre id pour que le Voter fonctionne correctement !!
    #[Route('/admin/medical_report/ajax_delete/{id}', name: 'ajax_medical_report_delete', methods: ['POST'])]
    #[IsGranted('ajaxDelete', subject: 'medicalReport')]
    public function ajaxDelete(Request $request,MedicalReport $medicalReport): Response
    {
        return $this->ajaxDeleteCRUD($request,'Animal');
    }
    
    #[Route('/admin/medical_report/ajax_datatable', name: 'ajax_medical_report_datatable')]
    #[IsGranted(new Expression(RoleExpressions::ALL))]
    public function ajax_datatable(Request $request): Response
    {
        return $this->ajaxDatatableCRUD($request);
    }

}

<?php

namespace App\Controller\Admin;
use App\Controller\Bootstrap\AdminLayoutController;
use App\Repository\AnimalRepository;
use App\Repository\FoodRepository;
use App\Repository\UserRepository;
use App\Security\RoleExpressions;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class AnimalFoodController extends AdminLayoutController
{

    protected string $entityName = 'AnimalFood';
    protected string $entityTitle = 'Nouriture';
    protected string $gender = 'f';
    protected string $render = 'global';


    #[Route('/admin/animal_food', name: 'app_admin_animal_food')]
    #[IsGranted(new Expression(RoleExpressions::ALL))]
    public function index(AnimalRepository $animalRepository,FoodRepository $foodRepository, UserRepository $userRepository): Response
    {
        $this->theme->addJavascriptFile('https://npmcdn.com/flatpickr@4.6.13/dist/l10n/fr.js');
        $this->theme->addJavascriptFile('js/dateRanges.js');


        $animals = $animalRepository->findBy([], ['name' => 'ASC']);
        $foods = $foodRepository->findBy([], ['name' => 'ASC']);
        $users = $userRepository->findByRole('ROLE_EMPLOYE');


        $additionalParams = [
            'animals' => $animals,
            'foods' => $foods,
            'users' => $users,


        ];

        return parent::indexCRUD($additionalParams);
    }

    #[Route('/admin/animal_food/new', name: 'app_admin_animal_food_new', methods: ['GET', 'POST'])]
    #[IsGranted(new Expression(RoleExpressions::ADMIN_OR_EMPLOYE))]
    public function new(Request $request): Response
    {
        return parent::newCRUD(  $request);
    }

    #[Route('/admin/animal_food/edit/{id}', name: 'app_admin_animal_food_edit', methods: ['GET', 'POST'])]
    #[IsGranted(new Expression(RoleExpressions::ADMIN_OR_EMPLOYE))]
    public function edit(string $id, Request $request,): Response
    {
        return $this->editCRUD($id, $request);
    }

    #[Route('/admin/animal_food/ajax_delete', name: 'ajax_animal_food_delete', methods: ['DELETE'])]
    #[IsGranted(new Expression(RoleExpressions::ADMIN_OR_EMPLOYE))]
    public function ajaxDelete(Request $request): Response
    {
        return $this->ajaxDeleteCRUD($request,'Animal');
    }

    #[Route('/admin/animal_food/ajax_datatable', name: 'ajax_animal_food_datatable')]
    #[IsGranted(new Expression(RoleExpressions::ALL))]
    public function ajax_datatable(Request $request): Response
    {
        return $this->ajaxDatatableCRUD($request);
    }


}

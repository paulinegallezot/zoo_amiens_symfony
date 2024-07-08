<?php

namespace App\Controller\Admin;
use App\Controller\Bootstrap\AdminLayoutController;
use App\Repository\AnimalRepository;
use App\Repository\FoodRepository;
use App\Repository\UserRepository;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(new Expression('is_granted("ROLE_ADMIN") or is_granted("ROLE_EMPLOYE")'))]
class AnimalFoodController extends AdminLayoutController
{

    protected string $entityName = 'AnimalFood';
    protected string $entityTitle = 'Nouriture';
    protected string $gender = 'f';
    protected string $render = 'global';


    #[Route('/admin/animal_food', name: 'app_admin_animal_food')]
    public function index(AnimalRepository $animalRepository,FoodRepository $foodRepository, UserRepository $userRepository): Response
    {
        $animals = $animalRepository->findBy([], ['name' => 'ASC']);
        $foods = $foodRepository->findBy([], ['name' => 'ASC']);
        $users = $userRepository->findByRole('ROLE_VETO');
        $additionalParams = [
            'animals' => $animals,
            'foods' => $foods,
            'users' => $users,


        ];

        return parent::indexCRUD($additionalParams);
    }

    #[Route('/admin/animal_food/new', name: 'app_admin_animal_food_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        return parent::newCRUD(  $request);
    }

    #[Route('/admin/animal_food/edit/{id}', name: 'app_admin_animal_food_edit', methods: ['GET', 'POST'])]
    public function edit(string $id, Request $request,): Response
    {
        return $this->editCRUD($id, $request);
    }

    #[Route('/admin/animal_food/ajax_delete', name: 'ajax_animal_food_delete', methods: ['DELETE'])]
    public function ajaxDelete(Request $request): Response
    {
        return $this->ajaxDeleteCRUD($request,'Animal');
    }
    
    #[Route('/admin/animal_food/ajax_datatable', name: 'ajax_animal_food_datatable')]
    public function ajax_datatable(Request $request): Response
    {
        return $this->ajaxDatatableCRUD($request);
    }

}

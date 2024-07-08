<?php

namespace App\Controller\Admin;
use App\Controller\Bootstrap\AdminLayoutController;
use App\Entity\Animal;
use App\Entity\AnimalImage;
use App\Form\AnimalType;
use App\Repository\HabitatRepository;
use App\Repository\RaceRepository;
use App\Service\ImageHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
class AnimalController extends AdminLayoutController
{

    protected string $entityName = 'Animal';
    protected string $gender = 'm';
    protected string $render = 'global';

    #[Route('/admin/animal', name: 'app_admin_animal')]
    public function index( RaceRepository $raceRepository,HabitatRepository $habitatRepository): Response
    {
        $races = $raceRepository->findBy([], ['name' => 'ASC']);

        $habitats = $habitatRepository->findBy([], ['name' => 'ASC']);
        $additionalParams = [
            'races' => $races,
            'habitats' => $habitats,
        ];

        return parent::indexCRUD($additionalParams);
    }

    #[Route('/admin/animal/new', name: 'app_admin_animal_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager,ImageHelper $imageHelper): Response
    {
        $animal = new Animal();
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->importAndSaveImage($animal, $form->get('image')->getData(), $imageHelper);

            $entityManager->persist($animal);
            $entityManager->flush();

            $this->addFlash('success', 'Animal ajouté avec succès.');

            if ($request->request->has('save_and_exit')) {
                return $this->redirectToRoute('app_admin_animal', [], Response::HTTP_SEE_OTHER);

            }else{
                return $this->redirectToRoute('app_admin_animal_edit', ['id' => $animal->getId()], Response::HTTP_SEE_OTHER);
            }

        }

        return $this->render('admin/global/new.html.twig', [
            'animal' => $animal,
            'form_template' => 'admin/animal/_form.html.twig',
            'form' => $form,
            'page_title' => "Animals / Ajouter un animal",
        ]);
    }

    #[Route('/admin/animal/edit/{id}', name: 'app_admin_animal_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Animal $animal, EntityManagerInterface $entityManager,ImageHelper $imageHelper): Response
    {



        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {

            // GESTION SUPPRESSION DES IMAGES
            $deleteImagesIds = $request->request->all('delete_images');

            if (!empty($deleteImagesIds)) {
                $imageHelper->deleteImages($deleteImagesIds,$this->getImageDirectory());
            }

            $this->importAndSaveImage($animal, $form->get('image')->getData(), $imageHelper);

            $entityManager->flush();

            $this->addFlash('success', 'Animal modifié avec succès.');

            if ($request->request->has('save_and_exit')) {
                return $this->redirectToRoute('app_admin_animal', [], Response::HTTP_SEE_OTHER);

            }
        }

        return $this->render('admin/global/edit.html.twig', [
            'animal' => $animal,
            'form' => $form->createView(),
            'form_template' => 'admin/animal/_form.html.twig',
            'page_title' => "Animals / Editer un animal",
            'imagesDirectory' => $this->getParameter('images_directory')
        ]);
    }

    #[Route('/admin/animal/food/{id}', name: 'app_admin_animal_food', methods: ['GET', 'POST'])]
    public function food(Request $request, Animal $animal, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnimalFoodType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            $this->addFlash('success', 'Alimentation modifiée avec succès.');

            if ($request->request->has('save_and_exit')) {
                return $this->redirectToRoute('app_admin_animal', [], Response::HTTP_SEE_OTHER);

            }
        }

        return $this->render('admin/animal/food.html.twig', [
            'animal' => $animal,
            'form' => $form->createView(),
            'page_title' => "Animals / Alimentation",
        ]);
    }

    #[Route('/admin/animal/ajax_delete', name: 'ajax_animal_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function ajaxDelete(Request $request): Response
    {
        return $this->ajaxDeleteCRUD($request,'Animal');
    }
    
    #[Route('/admin/animal/ajax_datatable', name: 'ajax_animal_datatable')]

    public function ajax_datatable(Request $request): Response
    {
        return $this->ajaxDatatableCRUD($request);
    }

    //---------------------------------------------
    private function importAndSaveImage(Animal $animal, $imageFile, ImageHelper $imageHelper): void
    {
        if ($imageFile) {
            $fileInfo = $imageHelper->prepareFileInfo($imageFile);
            $image = new AnimalImage();
            $image->setExtension($fileInfo['extension']);
            $image->setFilename($fileInfo['filename']);
            $animal->addImage($image);
            $imageHelper->saveImage($imageFile, $fileInfo, $this->getImageDirectory());
        }
    }
    private function getImageDirectory(): string
    {
        return $this->getParameter('images_directory').'/'.strtolower($this->entityName);
    }
}

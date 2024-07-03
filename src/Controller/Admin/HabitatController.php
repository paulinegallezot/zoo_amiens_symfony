<?php

namespace App\Controller\Admin;
use App\Controller\Bootstrap\AdminLayoutController;
use App\Entity\Habitat;
use App\Entity\HabitatImage;
use App\Form\HabitatType;
use App\Service\ImageHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HabitatController extends AdminLayoutController
{

    protected string $entityName = 'Habitat';
    protected string $gender = 'm';
    protected string $render = 'global';


    #[Route('/admin/habitat', name: 'app_admin_habitat')]
    public function index(): Response
    {
        return parent::indexCRUD();
    }

    #[Route('/admin/habitat/new', name: 'app_admin_habitat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,ImageHelper $imageHelper): Response
    {
        $habitat = new Habitat();
        $form = $this->createForm(HabitatType::class, $habitat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->importAndSaveImage($habitat, $form->get('image')->getData(), $imageHelper);

            $entityManager->persist($habitat);
            $entityManager->flush();

            $this->addFlash('success', 'Habitat ajouté avec succès.');

            if ($request->request->has('save_and_exit')) {
                return $this->redirectToRoute('app_admin_habitat', [], Response::HTTP_SEE_OTHER);

            }else{
                return $this->redirectToRoute('app_admin_habitat_edit', ['id' => $habitat->getId()], Response::HTTP_SEE_OTHER);
            }

        }

        return $this->render('admin/global/new.html.twig', [
            'habitat' => $habitat,
            'form_template' => 'admin/habitat/_form.html.twig',
            'form' => $form,
            'page_title' => "Habitats / Ajouter un habitat",
        ]);
    }

    #[Route('/admin/habitat/edit/{id}', name: 'app_admin_habitat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Habitat $habitat, EntityManagerInterface $entityManager,ImageHelper $imageHelper): Response
    {



        $form = $this->createForm(HabitatType::class, $habitat);
        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {

            // GESTION SUPPRESSION DES IMAGES
            $deleteImagesIds = $request->request->all('delete_images');

            if (!empty($deleteImagesIds)) {
                $imageHelper->deleteImages($deleteImagesIds,$this->getParameter('images_directory').'/animals');
            }


            $this->importAndSaveImage($habitat, $form->get('image')->getData(), $imageHelper);

            $entityManager->flush();

            $this->addFlash('success', 'Habitat modifié avec succès.');

            if ($request->request->has('save_and_exit')) {
                return $this->redirectToRoute('app_admin_habitat', [], Response::HTTP_SEE_OTHER);

            }
        }

        return $this->render('admin/global/edit.html.twig', [
            'habitat' => $habitat,
            'form' => $form->createView(),
            'form_template' => 'admin/habitat/_form.html.twig',
            'page_title' => "Habitats / Editer un habitat",

            'imagesDirectory' => $this->getParameter('images_directory')
        ]);
    }

    #[Route('/admin/habitat/ajax_delete', name: 'ajax_habitat_delete', methods: ['DELETE'])]
    public function ajaxDelete(Request $request): Response
    {
        return $this->ajaxDeleteCRUD($request,'Animal');
    }
    
    #[Route('/admin/habitat/ajax_datatable', name: 'ajax_habitat_datatable')]
    public function ajax_datatable(Request $request): Response
    {
        return $this->ajaxDatatableCRUD($request);
    }

    //---------------------------------------------
    private function importAndSaveImage(Habitat $habitat, $imageFile, ImageHelper $imageHelper): void
    {
        if ($imageFile) {
            $fileInfo = $imageHelper->prepareFileInfo($imageFile);
            $image = new HabitatImage();
            $image->setExtension($fileInfo['extension']);
            $image->setFilename($fileInfo['filename']);
            $habitat->addImage($image);
            $imageHelper->saveImage($imageFile, $fileInfo, $this->getParameter('images_directory').'/habitats');
        }
    }

}

<?php

namespace App\Controller\Bootstrap;

use App\Service\MetronicThemeHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Service\RepositoryHelper;

abstract class AdminLayoutController extends AbstractController
{
    public MetronicThemeHelper $theme;
    public RepositoryHelper $repositoryHelper;
    public EntityManagerInterface $entityManager;
    public UrlGeneratorInterface $urlGenerator;
    public SerializerInterface $serializer;

    protected string $entityName;
    protected string $gender;
    protected string $render;




    public function __construct(
        MetronicThemeHelper $theme,
        RepositoryHelper $repositoryHelper,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        SerializerInterface $serializer
        )
    {
        $this->theme = $theme;
        $this->repositoryHelper = $repositoryHelper;
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->serializer = $serializer;
        $this->init();

    }
    public function indexCRUD(): Response
    {
        $entityNameLower = strtolower($this->entityName);

        $this->theme->addVendors(['datatables']);
        $this->theme->addJavascriptFile('https://cdn.jsdelivr.net/npm/bootbox@6.0.0/dist/bootbox.min.js');
        $this->theme->addJavascriptFile('js/dataTable.js');
        $this->theme->addJavascriptFile("js/{$entityNameLower}/deleteItem.js");
        $this->theme->addJavascriptFile("js/{$entityNameLower}/dataTable.js");

        return $this->render("admin/{$entityNameLower}/index.html.twig", [
            'page_title'    => "Gérer les {$this->entityName}s",
            'jsCustomConfig' => [
                'deleteUrl' => "{$entityNameLower}/ajax_delete",
                'datatableUrl' => "{$entityNameLower}/ajax_datatable",
                'editUrl' => $this->urlGenerator->generate("app_admin_{$entityNameLower}_edit", ['id' => '__ID__']),
            ]
        ]);
    }
    protected function newCRUD(  Request $request): Response
    {
        $entityNameLower = strtolower($this->entityName);
        $renderDir = $this->getRenderDir();

        $entityClass = "App\\Entity\\" . ucfirst($this->entityName);
        $formClass = "App\\Form\\" . ucfirst($this->entityName) . "Type";

        $entity = new $entityClass();
        $form = $this->createForm($formClass, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            $this->addFlash('success', "{$this->entityName} ajouté".$this->getGender()." avec succès.");

            if ($request->request->has('save_and_exit')) {
                return $this->redirectToRoute("app_admin_{$entityNameLower}", [], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute("app_admin_{$entityNameLower}_edit", ['id' => $entity->getId()], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render("admin/{$renderDir}/new.html.twig", [
            'entity' => $entity,
            'form' => $form->createView(),
            'form_template' => "admin/{$entityNameLower}/_form.html.twig",
            'page_title' => "{$this->entityName} / Ajouter un".$this->getGender()."  {$this->entityName}",
        ]);
    }
    protected function editCRUD(string $id, Request $request): Response
    {
        $entityNameLower = strtolower($this->entityName);
        $renderDir = $this->getRenderDir();

        $entityClass = "App\\Entity\\" . ucfirst($this->entityName);
        $formClass = "App\\Form\\" . ucfirst($this->entityName) . "Type";

        $entity = $this->entityManager->getRepository($entityClass)->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('No ' . $this->entityName . ' found for id ' . $id);
        }

        $form = $this->createForm($formClass, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', ucfirst($this->entityName) . ' mise à jour avec succès.');

            if ($request->request->has('save_and_exit')) {
                return $this->redirectToRoute('app_admin_' . $entityNameLower, [], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('app_admin_' . $entityNameLower . '_edit', ['id' => $entity->getId()], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render("admin/{$renderDir}/edit.html.twig", [
            'entity' => $entity,
            'form' => $form->createView(),
            'form_template' => 'admin/' . $entityNameLower . '/_form.html.twig',
            'page_title' => ucfirst($this->entityName) . ' / Éditer un' . $this->getGender() . ' ' . strtolower($this->entityName),
        ]);
    }
    protected function ajaxDatatableCRUD(Request $request): Response
    {
        $entityRepository = $this->entityManager->getRepository("App\\Entity\\" . ucfirst($this->entityName));

        list($entities, $recordsFiltered) = $entityRepository->findInDatatable($request->query);


        $recordsTotal = $entityRepository->count([]);

        $entitiesData = $this->serializer->normalize($entities, 'json', ['groups' => 'default']);

        $jsonData = json_encode([
            'data' => $entitiesData,
            'recordsFiltered' => $recordsFiltered,
            'recordsTotal' => $recordsTotal,
        ]);

        return new Response($jsonData, Response::HTTP_OK, [
            'Content-Type' => 'application/json',
        ]);
    }
    protected function ajaxDeleteCRUD(Request $request,string $counterName): Response
    {
        if (!$this->isCsrfTokenValid('delete-item', $request->headers->get('X-CSRF-Token'))) {
            return new Response(json_encode(['success' => false, 'message' => 'Jeton CSRF invalide']), Response::HTTP_OK, [
                'Content-Type' => 'application/json'
            ]);
        }

        $ids = $request->request->all('ids');
        if (empty($ids)) {
            return new Response(json_encode(['success' => false, 'message' => 'Aucun ID fourni']), Response::HTTP_OK, [
                'Content-Type' => 'application/json'
            ]);
        }

        $entityClass = "App\\Entity\\" . ucfirst($this->entityName);
        $entityRepository = $this->entityManager->getRepository($entityClass);

        foreach ($ids as $id) {
            $entity = $entityRepository->find($id);
            if ($entity) {
                $getter = 'getCounter' . $counterName;
                if (method_exists($entity, $getter) && $entity->$getter() > 0) {
                    return new Response(json_encode(['success' => false, 'message' => "Des éléments sont liés à {$this->entityName}"]), Response::HTTP_OK, [
                        'Content-Type' => 'application/json'
                    ]);
                } else {
                    $this->entityManager->remove($entity);
                    $this->entityManager->flush();
                }
            } else {
                return new Response(json_encode(['success' => false, 'message' => 'Une erreur s\'est produite']), Response::HTTP_OK, [
                    'Content-Type' => 'application/json'
                ]);
            }
        }

        return new Response(json_encode(['success' => true, 'message' => 'Suppression effectuée']), Response::HTTP_OK, [
            'Content-Type' => 'application/json'
        ]);
    }

    public function init(): void
    {
        $this->initDarkSidebarLayout();


    }
    private function getRenderDir(): string
    {
        if ($this->render == 'global') {
            return 'global';
        } else {
            return strtolower($this->entityName);
        }
    }

    private function getGender(): string
    {
        if ($this->gender == 'f') {
            return 'e';
        } else {
            return '';
        }
    }

    public function initDarkSidebarLayout()
    {
        $this->theme->addHtmlAttribute('body', 'data-kt-app-layout', 'dark-sidebar');
        $this->theme->addHtmlAttribute('body', 'data-kt-app-header-fixed', 'false');
        $this->theme->addHtmlAttribute('body', 'data-kt-app-sidebar-enabled', 'true');
        $this->theme->addHtmlAttribute('body', 'data-kt-app-sidebar-fixed', 'true');
        $this->theme->addHtmlAttribute('body', 'data-kt-app-sidebar-hoverable', 'true');
        $this->theme->addHtmlAttribute('body', 'data-kt-app-sidebar-push-header', 'true');
        $this->theme->addHtmlAttribute('body', 'data-kt-app-sidebar-push-toolbar', 'true');
        $this->theme->addHtmlAttribute('body', 'data-kt-app-sidebar-push-footer', 'true');
        $this->theme->addHtmlAttribute('body', 'data-kt-app-toolbar-enabled', 'true');

        $this->theme->addHtmlClass('body', 'app-default');
    }




}

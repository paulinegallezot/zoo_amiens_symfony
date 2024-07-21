<?php

namespace App\Controller\Admin;

use App\Controller\Bootstrap\AdminLayoutController;
use App\Service\DocumentationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;

class DocumentationController extends AdminLayoutController
{
    protected string $entityTitle = 'Documentation';

    #[Route('/admin/doc', name: 'generate_doc')]
    public function generateDoc(DocumentationService $documentationService): Response
    {
        $docs = $documentationService->getAllDocumentation();

        return $this->render('documentation/generate.html.twig', [
            'docs' => $docs,
            'page_title' => 'Documentation'
        ]);
    }
}

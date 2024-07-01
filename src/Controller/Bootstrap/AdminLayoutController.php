<?php

namespace App\Controller\Bootstrap;

use App\Service\MetronicThemeHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Service\RepositoryHelper;
/*use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;*/
class AdminLayoutController extends AbstractController
{
    public MetronicThemeHelper $theme;
    public RepositoryHelper $repositoryHelper;
    public EntityManagerInterface$entityManager; // On en aura besoin souvent cela evite l'injection

    public function __construct(MetronicThemeHelper $theme,RepositoryHelper $repositoryHelper,EntityManagerInterface $entityManager)
    {
        $this->theme = $theme;
        $this->repositoryHelper = $repositoryHelper;
        $this->entityManager = $entityManager;
        $this->init();
       /* $token = $csrfTokenManager->getToken('item_delete')->getValue();*/
 /*       $this->theme->setCsrfToken($token);*/

    }

    public function init()
    {
        // 1) Light sidebar layout (_default.html.twig)
        // $this->initLightSidebarLayout();

        // 2) Dark sidebar layout (_default.html.twig)
        $this->initDarkSidebarLayout();

        // 3) Dark header layout (_default_header_layout.html.twig)
        // $this->initDarkHeaderLayout();

        // 4) Light header layout (_default_header_layout.html.twig)
        // $this->initLightHeaderLayout();

        // Init global assets for default layout
        $this->initAssets();
    }

    public function initAssets()
    {
        # Include global vendors
        //$this->theme->addVendors(['datatables']);
        //$this->theme->addVendors(['datatables', 'fullcalendar']);

        # Include global javascript files
        //$this->theme->addJavascriptFile('js/widgets.bundle.js');
        //$this->theme->addJavascriptFile('js/custom/apps/chat/chat.js');
        //$this->theme->addJavascriptFile('js/custom/utilities/modals/upgrade-plan.js');
        //$this->theme->addJavascriptFile('js/custom/utilities/modals/create-app.js');
        //$this->theme->addJavascriptFile('js/custom/utilities/modals/users-search.js');
        //$this->theme->addJavascriptFile('js/custom/utilities/modals/new-target.js');
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

    public function initLightSidebarLayout()
    {
        $this->theme->addHtmlAttribute('body', 'data-kt-app-layout', 'light-sidebar');
        $this->theme->addHtmlAttribute('body', 'data-kt-app-header-fixed', 'false');
        $this->theme->addHtmlAttribute('body', 'data-kt-app-sidebar-enabled', 'true');
        $this->theme->addHtmlAttribute('body', 'data-kt-app-sidebar-fixed', 'false');
        $this->theme->addHtmlAttribute('body', 'data-kt-app-sidebar-hoverable', 'true');
        $this->theme->addHtmlAttribute('body', 'data-kt-app-sidebar-push-header', 'true');
        $this->theme->addHtmlAttribute('body', 'data-kt-app-sidebar-push-toolbar', 'true');
        $this->theme->addHtmlAttribute('body', 'data-kt-app-sidebar-push-footer', 'true');
        $this->theme->addHtmlAttribute('body', 'data-kt-app-toolbar-enabled', 'true');

        $this->theme->addHtmlClass('body', 'app-default');
    }

    public function initDarkHeaderLayout()
    {
        $this->theme->addHtmlAttribute('body', 'data-kt-app-layout', 'dark-header');
        $this->theme->addHtmlAttribute('body', 'data-kt-app-header-fixed', 'true');
        $this->theme->addHtmlAttribute('body', 'data-kt-app-toolbar-enabled', 'true');

        $this->theme->addHtmlClass('body', 'app-default');

    }

    public function initLightHeaderLayout()
    {
        $this->theme->addHtmlAttribute('body', 'data-kt-app-layout', 'light-header');
        $this->theme->addHtmlAttribute('body', 'data-kt-app-header-fixed', 'true');
        $this->theme->addHtmlAttribute('body', 'data-kt-app-toolbar-enabled', 'true');

        $this->theme->addHtmlClass('body', 'app-default');
    }
}

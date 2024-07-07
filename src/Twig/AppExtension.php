<?php
namespace App\Twig;

use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;

class AppExtension extends AbstractExtension
{


    public function __construct(private readonly RequestStack $requestStack, private Security $security)
    {

    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_current_route_return_active', [$this, 'isCurrentRouteReturnActive']),
            new TwigFunction('current_user', [$this, 'getCurrentUser']),
        ];
    }
    public function isCurrentRouteReturnActive(string $route): string
    {
       /* Pour le menu _menu.html.twig, nous avons besoin de savoir si la route actuelle est celle du lien du menu.*/

        $currentRoute = $this->requestStack->getCurrentRequest()->attributes->get('_route');
            return (strpos($currentRoute, $route)  === 0) ? 'active' : '';

    }

    public function getCurrentUser(): UserInterface
    {
        return $this->security->getUser();
    }
}

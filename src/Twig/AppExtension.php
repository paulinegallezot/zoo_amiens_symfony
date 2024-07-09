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
        if ($currentRoute) {
            // Diviser les routes en segments
            $currentRouteSegments = explode('_', $currentRoute);
            $routeSegments = explode('_', $route);

            // Comparer les segments de la route
            if ($currentRouteSegments === $routeSegments) {
                return 'active';
            }

            // Vérifier si la route actuelle commence par les segments de la route donnée et a un segment supplémentaire
            if (count($currentRouteSegments) > count($routeSegments)) {
                $baseRoute = implode('_', array_slice($currentRouteSegments, 0, count($routeSegments)));
                if ($baseRoute === $route) {
                    $nextSegment = $currentRouteSegments[count($routeSegments)];
                    //if (in_array($nextSegment, ['edit', 'new'])) {
                    if ($nextSegment === 'food') return ''; // C'est pa beau, pour éviter que animal_food active app_admin_animal
                        return 'active';
                    //}
                }
            }
        }

        return '';


        //si currentRoute == app_admin_animal_food_edit et $route == app_admin_animal_food alors on active app_admin_animal_food et pas app_admin_animal
       // il faut peux etre comparer les deux routes avec strpos et le nombre de _ pour eviter les erreurs
            return (strpos($currentRoute, $route)  === 0) ? 'active' : '';

    }

    public function getCurrentUser(): UserInterface
    {
        return $this->security->getUser();
    }
}

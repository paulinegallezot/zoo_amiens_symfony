<?php
namespace App\Twig;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Symfony\Component\HttpFoundation\RequestStack;
class AppExtension extends AbstractExtension
{
    private $requestStack;
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_current_route_return_active', [$this, 'isCurrentRouteReturnActive']),
        ];
    }
    public function isCurrentRouteReturnActive(string $route): string
    {
        $currentRoute = $this->requestStack->getCurrentRequest()->attributes->get('_route');
        return (strpos($currentRoute, $route)  === 0) ? 'active' : '';

    }
}

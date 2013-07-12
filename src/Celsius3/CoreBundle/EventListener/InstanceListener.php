<?php
namespace Celsius3\CoreBundle\EventListener;
use Celsius3\CoreBundle\Exception\InstanceNotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class InstanceListener
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ($exception instanceof InstanceNotFoundException) {
            $response = new RedirectResponse(
                    $this->router->generate('directory'));
            $event->setResponse($response);
        }
    }
}
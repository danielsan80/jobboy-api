<?php
declare(strict_types=1);

namespace JobBoy\Process\Api\EventSubscriber;

use JobBoy\Process\Api\Controller\JobBoyApiController;
use JobBoy\Process\Api\Response\Error;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class JobBoyApiControllerEventSubscriber implements EventSubscriberInterface
{
    private const HANDLE_EXCEPTION = 'handle_exception';

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        // when a controller class defines multiple action methods, the controller
        // is returned as [$controllerInstance, 'methodName']
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if (!$controller[0] instanceof JobBoyApiController) {
            return;
        }

        $event->getRequest()->attributes->set(self::HANDLE_EXCEPTION, true);
    }

    public function onKernelException(ExceptionEvent $event)
    {

        if (!$event->getRequest()->attributes->get(self::HANDLE_EXCEPTION, false)) {
            return;
        }

        $e = $event->getThrowable();
        $error = new Error($e->getMessage());

        $response = new JsonResponse($error->normalize());
        $event->setResponse($response);
    }


    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}

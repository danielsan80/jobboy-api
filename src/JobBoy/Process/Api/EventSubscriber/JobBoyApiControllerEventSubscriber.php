<?php
declare(strict_types=1);

namespace JobBoy\Process\Api\EventSubscriber;

use JobBoy\Process\Api\Controller\JobBoyApiController;
use JobBoy\Process\Api\Response\Error;
use JobBoy\Process\Api\Response\Unauthorized;
use JobBoy\Process\Api\Security\RequiredRoleProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class JobBoyApiControllerEventSubscriber implements EventSubscriberInterface
{
    private const HANDLE_EXCEPTION = 'handle_exception';

    private $authorizationChecker;
    private $requiredRoleProvider;

    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        RequiredRoleProvider $requiredRoleProvider
    )
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->requiredRoleProvider = $requiredRoleProvider;
    }

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

        $this->denyAccessUnlessAuthorized();

    }

    public function onKernelException(ExceptionEvent $event)
    {

        if (!$event->getRequest()->attributes->get(self::HANDLE_EXCEPTION, false)) {
            return;
        }

        $e = $event->getException();

        if ($event->getResponse()->isForbidden()) {
            $unauthorized = new Unauthorized($e->getMessage());
            $event->setResponse(new JsonResponse($unauthorized->normalize()));
            return;
        }

        $error = new Error($e->getMessage());
        $event->setResponse(new JsonResponse($error->normalize()));

    }

    private function denyAccessUnlessAuthorized(): void
    {
        $requiredRole = $this->requiredRoleProvider->get();
        if (!$this->authorizationChecker->isGranted($requiredRole)) {
            $exception = new AccessDeniedException('Access Denied.');
            $exception->setAttributes($requiredRole);
            $exception->setSubject(null);

            throw $exception;
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}

<?php

namespace App\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;

class MobileExceptionListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => ['getMobileExceptionsORM', EventPriorities::POST_VALIDATE],
        ];
    }

    /**
     * @param ExceptionEvent $event
     */
    public function getMobileExceptionsORM(ExceptionEvent $event): void
    {
        $getPath = $event->getRequest()->getPathInfo();

        if (strpos($getPath, '/api/mobiles/') === false || !$event->getRequest()->isMethodSafe(false)) {
            return;
        } else {
            $msg = 'Attention, cette fiche mobile n\'existe pas';
            $response = new JsonResponse(
                $msg,
                '404'
            );
        }

        $response->headers->set('Content-Type', 'application/ld+json');
        $event->setResponse($response);
    }
}
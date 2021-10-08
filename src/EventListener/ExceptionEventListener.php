<?php

namespace App\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;


class ExceptionEventListener implements EventSubscriberInterface
{
    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => ['getExceptionMessage', EventPriorities::POST_READ],
        ];
    }

    public function getExceptionMessage(ExceptionEvent $event): void
    {
        $exception_response = $event->getThrowable();
        $msg = $exception_response->getMessage();

        if($exception_response){
            $response = new JsonResponse(
                $msg,
                '404'
            );
            $response->headers->set('Content-Type', 'application/ld+json');
            $event->setResponse($response);
        }
    }
}
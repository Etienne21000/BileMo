<?php

namespace App\EventListener;

use ApiPlatform\Core\EventListener\EventPriorities;
use ApiPlatform\Core\Exception\InvalidArgumentException;
use ApiPlatform\Core\Validator\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionEventListener implements EventSubscriberInterface
{
    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents() {
        return [KernelEvents::EXCEPTION => ['getExceptionMessage', EventPriorities::POST_READ],];
    }

    public function getExceptionMessage(ExceptionEvent $event): void
    {
        $exception_response = $event->getThrowable();
        $msg = $exception_response->getMessage();
        if($exception_response instanceof ValidationException) {
            $response = new JsonResponse(
                $msg,
                '400'
            );
        } elseif($exception_response instanceof InvalidArgumentException) {
            $trace = $exception_response->getTrace();
            $entityTypeWrong = $trace[0]['args'][0];
            $response = new JsonResponse(
                'Attention, la valeur du champ '.$entityTypeWrong.' n\'est pas la bonne',
                '400'
            );
        } elseif($exception_response instanceof BadRequestException){
            $response = new JsonResponse(
                $msg,
                '404'
            );
        } elseif($exception_response instanceof HttpExceptionInterface) {
            $response = new JsonResponse(
                $msg,
                '403'
            );
        } elseif($exception_response instanceof NotFoundHttpException) {
            $response = new JsonResponse(
                'Le endpoint demandé n\'éxiste pas',
                '404'
            );
        } else {
            $response = new JsonResponse(
              'Une erreur s\'est produite',
              '500'
            );
        }
        $response->headers->set('Content-Type', 'application/ld+json');
        $event->setResponse($response);
    }
}
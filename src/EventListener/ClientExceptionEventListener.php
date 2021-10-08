<?php
/**
 * BileMo
 * ClientExceptionEventListener.php
 *
 * PHP Version 5
 *
 * @category Production
 * @package  Default
 * @date     08/10/2021 15:58
 * @license  http://BileMo.com/license.txt BileMo License
 * @version  GIT: 1.0
 * @link     http://BileMo.com/
 */

namespace App\EventListener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ApiPlatform\Core\EventListener\EventPriorities;
//use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\JsonResponse;

class ClientExceptionEventListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => ['getClientException', EventPriorities::POST_VALIDATE],
        ];
    }

    /**
     * @param ExceptionEvent $event
     */
    public function getClientException(ExceptionEvent $event): void
    {
        $getPath = $event->getRequest()->getPathInfo();

        if (strpos($getPath, '/api/clients/') === false || !$event->getRequest()->isMethodSafe(false)) {
            return;
        }

        $msg = 'Attention, ce client n\'existe pas';
        $response = new JsonResponse(
            $msg,
            '404'
        );
        $response->headers->set('Content-Type', 'application/ld+json');
        $event->setResponse($response);
    }
}
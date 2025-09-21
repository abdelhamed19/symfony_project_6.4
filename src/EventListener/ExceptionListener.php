<?php

namespace App\EventListener;

use App\Services\RestHelperService;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionListener
{
    public function __construct(private RestHelperService $rest) {}
    
    public function onKernelException($event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof NotFoundHttpException && str_contains($exception->getFile(), 'ArgumentResolver')) {
            $responseArray = $this
                ->rest
                ->failed()
                ->addMessage('Resource not found')
                ->getResponse();

            $response = new JsonResponse($responseArray, 404);
            $event->setResponse($response);
        }
    }
}

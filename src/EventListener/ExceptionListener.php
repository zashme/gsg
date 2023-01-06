<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Exception\UserException;
use App\Exception\ValidationErrorsException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $response = new JsonResponse();

        // HttpExceptionInterface is a special type of exception that holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->setData(['error' => $exception->getMessage()]);
            $response->headers->replace($exception->getHeaders());

        } elseif ($exception instanceof UserException) {
            $response->setStatusCode($exception->getCode());
            $response->setData(['error' => $exception->getMessage()]);

        } elseif ($exception instanceof ValidationErrorsException) {
            // Process validation errors
            foreach ($exception->errorConstraints as $constraint) {
                $validationIssues[] = [
                    'param' => $constraint->getPropertyPath(),
                    'error' => $constraint->getMessage()
                ];
            }
            $response->setStatusCode($exception->getCode());
            $response->setData(['error' => $validationIssues ?? null]);

        } else {
            // All other exceptions will return 500 Internal server error
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}

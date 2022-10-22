<?php

declare(strict_types=1);

namespace App\Shared\Http\Symfony;

use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class ApiController extends AbstractFOSRestController
{
    public function throwNotFound(string $message): void
    {
        throw new NotFoundHttpException($message);
    }

    public function respond(mixed $data): Response
    {
        $view = View::create();

        $context = new Context();
        $context->enableMaxDepth();
        $view->setContext($context);
        $view->setData($data);

        return $this->handleView($view);
    }
}

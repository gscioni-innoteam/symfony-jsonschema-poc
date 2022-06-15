<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\DoSomethingDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DoSomethingController extends AbstractController
{
    /**
     * To activate a json-schema validation: add a _schema_validator into defaults.
     * To auto-populate a DTO: just insert it in DI. You MUST be sure that all properties are compatible between json-schema declaration and your DTO.
     */
    #[Route('/do-something', defaults: ['_schema_validator' => 'do-something-test'], methods: [Request::METHOD_POST])]
    public function index(
        DoSomethingDTO $dto
    ): JsonResponse {
        // we have a complete and hydrated DTO :-)
        return new JsonResponse($dto);
    }
}

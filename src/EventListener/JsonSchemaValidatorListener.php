<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Validator\JsonSchema\Exception\JsonSchemaValidationException;
use App\Validator\JsonSchema\JsonSchemaValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class JsonSchemaValidatorListener
{
    private const ROUTE_PARAM = '_schema_validator';

    private JsonSchemaValidatorInterface $jsonSchemaValidator;

    public function __construct(JsonSchemaValidatorInterface $jsonSchema)
    {
        $this->jsonSchemaValidator = $jsonSchema;
    }

    public function onJsonSchemaValidation(RequestEvent $event): void
    {
        if ($event->getResponse() instanceof Response) {
            return;
        }

        $request = $event->getRequest();

        if ($this->isRequestSupported($request)) {
            try {
                $this->jsonSchemaValidator->validateAgainstSchema(
                    (string) $request->attributes->get(self::ROUTE_PARAM), // @phpstan-ignore-line
                    $this->getContent($request)
                );
            } catch (JsonSchemaValidationException $e) {
                $errorMessages = array_map(
                    static fn (array $el) => sprintf(
                        "Property '%s' violated a validation constraint: '%s'",
                        $el['property'],
                        $el['message']
                    ),
                    $e->getErrors()
                );

                $event->setResponse(
                    new JsonResponse([
                        'success' => false,
                        'message' => sprintf('%s: %s', $e->getMessage(), implode("\n ", $errorMessages)),
                    ], Response::HTTP_BAD_REQUEST)
                );
            }
        }
    }

    private function isRequestSupported(Request $request): bool
    {
        return $this->isRequestMethodSupported($request) && $request->attributes->has((string) self::ROUTE_PARAM);
    }

    private function isRequestMethodSupported(Request $request): bool
    {
        return in_array($request->getMethod(), [
            Request::METHOD_POST,
            Request::METHOD_PUT,
            Request::METHOD_PATCH,
            Request::METHOD_DELETE,
        ], false);
    }

    private function getContent(Request $request): string
    {
        return (string) $request->getContent();
    }
}

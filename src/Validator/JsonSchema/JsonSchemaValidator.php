<?php

declare(strict_types=1);

namespace App\Validator\JsonSchema;

use App\Validator\JsonSchema\Exception\JsonSchemaValidationException;
use JsonSchema\SchemaStorage;
use JsonSchema\Uri\UriResolver;
use JsonSchema\Uri\UriRetriever;
use JsonSchema\Validator;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * @codeCoverageIgnore
 */
class JsonSchemaValidator implements JsonSchemaValidatorInterface
{
    private Validator $validator;
    private SchemaStorage $schemaStorage;
    private string $basePath;

    public function __construct(string $basePath)
    {
        $this->validator = new Validator();
        $this->schemaStorage = new SchemaStorage(new UriRetriever(), new UriResolver());
        $this->basePath = $basePath;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAgainstSchema(string $schemaId, string $jsonString): void
    {
        $schema = $this->loadSchema($schemaId);
        $jsonInput = (object) json_decode($jsonString);
        $this->validator->validate($jsonInput, $schema);

        if (!$this->validator->isValid()) {
            /** @var array<array-key, mixed> $errors */
            $errors = $this->validator->getErrors();

            throw new JsonSchemaValidationException($errors);
        }
    }

    private function loadSchema(string $schemaId): object
    {
        $filePath = sprintf('%s/%s.json', $this->basePath, $schemaId);

        if (!file_exists($filePath)) {
            throw new FileNotFoundException(null, 0, null, $filePath);
        }

        return $this->schemaStorage->resolveRef("file://{$filePath}");
    }
}

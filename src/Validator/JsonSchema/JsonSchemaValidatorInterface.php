<?php

declare(strict_types=1);

namespace App\Validator\JsonSchema;

use App\Validator\JsonSchema\Exception\JsonSchemaValidationException;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

interface JsonSchemaValidatorInterface
{
    /**
     * Validates input Json String against a JsonSchema defined in
     * Infrastructure/Communication/Http/JsonSchema/Schema/<$schemaId>.json.
     *
     * @param string $schemaId   SchemaId. Must match with filename in filesystem
     * @param string $jsonString Input Json String to be validated
     *
     * @throws JsonSchemaValidationException thrown if validation does not pass
     * @throws FileNotFoundException         thrown if schema is not valid or is not found
     *
     * @see http://jsonschema.net
     * @see http://json-schema.org
     */
    public function validateAgainstSchema(string $schemaId, string $jsonString): void;
}

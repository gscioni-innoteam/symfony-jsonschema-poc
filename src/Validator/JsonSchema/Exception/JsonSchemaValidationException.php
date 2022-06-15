<?php

declare(strict_types=1);

namespace App\Validator\JsonSchema\Exception;

/**
 * @codeCoverageIgnore
 */
class JsonSchemaValidationException extends \RuntimeException
{
    private array $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct('Invalid Json');
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}

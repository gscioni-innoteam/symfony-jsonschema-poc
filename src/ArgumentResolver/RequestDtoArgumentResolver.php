<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use App\DTO\RequestDtoInterface;
use App\Exception\RequestValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestDtoArgumentResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private DenormalizerInterface $denormalizer,
        private ValidatorInterface $validator
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return is_subclass_of((string) $argument->getType(), RequestDtoInterface::class);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $payload = json_decode((string) $request->getContent(), true);

        $request = $this->denormalizer->denormalize($payload, (string) $argument->getType(), null, [
            AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
        ]);

        /**
         * Here we can validate every Assert() we need to use to complain domain logics.
         */
        $violations = $this->validator->validate($request);

        if ($violations->count()) {
            throw new RequestValidationException($violations);
        }

        yield $request;
    }
}

<?php

declare(strict_types=1);

namespace App\DTO;

class DoSomethingDTO implements RequestDtoInterface
{
    public string $name;

    public string $description;

    public string $url;
}

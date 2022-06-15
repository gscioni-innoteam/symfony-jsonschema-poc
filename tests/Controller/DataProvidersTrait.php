<?php

declare(strict_types=1);

namespace App\Tests\Controller;

trait DataProvidersTrait
{
    public function invalidPayloadDataProvider(): iterable
    {
        yield [
            [
                'name' => 2,
                'description' => 'a test description',
                'url' => 'https://atesturl.com',
            ],
            'Invalid Json: Property \'name\' violated a validation constraint: \'Integer value found, but a string is required\'',
        ];

        yield [
            [
                'name' => 'a test name',
                'description' => 2,
                'url' => 'https://atesturl.com',
            ],
            'Invalid Json: Property \'description\' violated a validation constraint: \'Integer value found, but a null or a string is required\'',
        ];

        yield [
            [
                'name' => 'a test name',
                'description' => 'a test description',
                'url' => 'wrongurl',
            ],
            'Invalid Json: Property \'url\' violated a validation constraint: \'Does not match the regex pattern ^(http|https)://\'',
        ];
    }

    public function validPayloadDataProvider(): iterable
    {
        yield [
            [
                'name' => 'a test name',
                'description' => 'a test description',
                'url' => 'https://atesturl.com',
            ],
        ];

        yield [
            [
                'name' => 'a test name',
                'url' => 'https://atesturl.com',
            ],
        ];
    }
}

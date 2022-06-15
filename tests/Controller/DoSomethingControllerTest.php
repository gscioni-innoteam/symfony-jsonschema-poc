<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use function json_decode;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
class DoSomethingControllerTest extends WebTestCase
{
    use DataProvidersTrait;
    
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
    }

    /**
     * @dataProvider validPayloadDataProvider
     */
    public function testShouldPostCorrectly(array $payload): void
    {
        $this->client->jsonRequest('POST', '/do-something', $payload);

        $response = $this->client->getResponse();

        $responseContent = $response->getContent();

        $content = json_decode($responseContent, true, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals($payload, $content);
    }

    /**
     * @dataProvider invalidPayloadDataProvider
     */
    public function testShouldHaveBadResponse(array $payload, string $errorMessage): void
    {
        $this->client->jsonRequest('POST', '/do-something', $payload);

        $response = $this->client->getResponse();

        $responseContent = $response->getContent();

        $content = json_decode($responseContent, true, 512, JSON_THROW_ON_ERROR);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertStringContainsString($errorMessage, $content['message']);
    }
}

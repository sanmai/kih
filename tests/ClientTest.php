<?php

declare(strict_types=1);

namespace KiH\Tests;

use GuzzleHttp\Client as HttpClient;
use KiH\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;

class ClientTest extends TestCase
{
    /**
     * @test
     */
    public function getFolder()
    {
        $httpClient = $this->createMock(HttpClient::class);
        $httpClient->expects(
            $this->once()
        )
            ->method('request')
            ->with('GET', 'https://api.onedrive.com/v1.0/shares/u!aHR0cDovL2V4YW1wbGUuY29t/root/children?select=audio%2CcreatedDateTime%2Cfile%2Cid%2Csize%2CwebUrl&orderby=lastModifiedDateTime+desc&top=10')
            ->willReturn($this->createConfiguredMock(MessageInterface::class, [
                'getBody' => $this->createMock(StreamInterface::class)
            ]));

        $client = new Client($httpClient, 'http://example.com');
        $client->getFolder();
    }
}

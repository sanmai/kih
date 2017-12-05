<?php

declare(strict_types=1);

namespace KiH\Tests\Action;

use KiH\Action\Media as Action;
use KiH\Client;
use KiH\Entity\Media as Entity;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Response;

class MediaTest extends TestCase
{
    /**
     * @test
     */
    public function invoke()
    {
        /** @var Request|\PHPUnit_Framework_MockObject_MockObject $request */
        $request = $this->getMockBuilder(Request::class)
            ->setMethods(['getAttribute'])
            ->getMockForAbstractClass();
        $request->expects(
            $this->once()
        )->method('getAttribute')
            ->with('id')
            ->willReturn('B6C46FF0A72F8DB!703491');

        /** @var Client|\PHPUnit_Framework_MockObject_MockObject $client */
        $client = $this->getMockBuilder(Client::class)
            ->setMethods(['getMedia'])
            ->getMockForAbstractClass();
        $client->expects(
            $this->once()
        )->method('getMedia')
            ->with('B6C46FF0A72F8DB!703491')
            ->willReturn(new Entity('http://example.com/media/B6C46FF0A72F8DB%25703491'));

        $action = new Action($client);

        $response = $action($request, new Response());

        $this->assertEquals(
            'http://example.com/media/B6C46FF0A72F8DB%25703491',
            $response->getHeaderLine('Location')
        );
    }
}
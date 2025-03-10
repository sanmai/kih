<?php declare(strict_types=1);

namespace KiH\Tests\Generator;

use DateTime;
use KiH\Entity\Feed;
use KiH\Entity\Item;
use KiH\Generator\Rss;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Slim\Interfaces\RouteParserInterface;
use function array_merge;
use function http_build_query;

class RssTest extends TestCase
{
    /**
     * @test
     */
    public function generate() : void
    {
        /** @var RouteParserInterface|MockObject $router */
        $router = $this->createMock(RouteParserInterface::class);
        $router->expects(
            $this->any()
        )->method('urlFor')
            ->willReturnCallback(static function (string $name, array $params) {
                return 'http://example.com/index.php?'
                    . http_build_query(array_merge(['page' => $name], $params));
            });

        $folder = new Feed([
            new Item(
                'B6C46FF0A72F8DB!703491',
                'Test Episode',
                new DateTime('2017-05-05T19:15:33.14Z'),
                'https://1drv.ms/u/s!Atv4cgr_RmwLqvgDHXuWoLPkN7ccQQ',
                3379032,
                'audio/mpeg',
                'Hello, <b>world</b>!'
            ),
        ]);

        $rss = new Rss($router, [
            'title' => 'Test Title',
            'logo' => 'http://example.com/logo.png',
        ]);

        $this->assertXmlStringEqualsXmlFile(
            __DIR__ . '/fixtures/rss.xml',
            $rss->generate($folder)
        );
    }
}

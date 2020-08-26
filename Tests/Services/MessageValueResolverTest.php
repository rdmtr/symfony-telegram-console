<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Tests\Services;

use Generator;
use PHPUnit\Framework\TestCase;
use Rdmtr\TelegramConsole\Services\MessageValueResolver;
use Rdmtr\TelegramConsole\Api\Objects\Message;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * Class MessageValueResolverTest
 */
class MessageValueResolverTest extends TestCase
{
    /**
     * @var MessageValueResolver
     */
    private $resolver;

    /**
     * @param string $type
     *
     * @dataProvider resolveProvider
     */
    public function testResolve(string $type): void
    {
        $message = $this->resolver->resolve($this->getRequest($type), $this->getMetadataMock())->current();

        $this->assertSame(1, $message->getId());
        $this->assertSame($type, $message->getText());
        $this->assertSame(10, $message->getUser()->getId());
        $this->assertSame('johndoe', $message->getUser()->getUsername());
        $this->assertSame(-11, $message->getChat()->getId());
    }

    /**
     * @return void
     */
    public function testResolveNested(): void
    {
        $message = $this->resolver->resolve($this->getRequest('with_reply'), $this->getMetadataMock())->current();

        $this->assertTrue($message->isBotMessageReply());

        $this->assertTrue($message->getReplyToMessage()->isCommand());
        $this->assertSame('/start', $message->getReplyToMessage()->getText());
        $this->assertSame(11, $message->getReplyToMessage()->getUser()->getId());
        $this->assertSame('botname', $message->getReplyToMessage()->getUser()->getUsername());
        $this->assertSame('group', $message->getReplyToMessage()->getChat()->getType());
    }

    /**
     * @return Generator
     */
    public function resolveProvider(): Generator
    {
        yield ['private'];
        yield ['group'];
        yield ['with_reply'];
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->resolver = new MessageValueResolver();
    }

    /**
     * @param string $filePostfix
     *
     * @return Request
     */
    private function getRequest(string $filePostfix): Request
    {
        return new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            file_get_contents(str_replace('.php', '_'.$filePostfix.'.json', __FILE__))
        );
    }

    /**
     * @return ArgumentMetadata
     */
    private function getMetadataMock(): ArgumentMetadata
    {
        $metadataMock = $this->createMock(ArgumentMetadata::class);
        $metadataMock->method('getType')->willReturn(Message::class);

        return $metadataMock;
    }
}
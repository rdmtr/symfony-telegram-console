<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Tests\Services;

use DateTime;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Rdmtr\TelegramConsole\Api\Objects\Chat;
use Rdmtr\TelegramConsole\Api\Objects\Message;
use Rdmtr\TelegramConsole\Api\Objects\User;
use Rdmtr\TelegramConsole\Services\Bot;
use Rdmtr\TelegramConsole\Services\CommandInterface;
use Rdmtr\TelegramConsole\Services\CommandRegistry;
use Rdmtr\TelegramConsole\Services\MessageHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class MessageHandlerTest
 */
class MessageHandlerTest extends TestCase
{
    /**
     * @var MessageHandler
     */
    private $messageHandler;

    /**
     * @var MockObject|Bot
     */
    private $bot;

    /**
     * @var MockObject|CommandInterface
     */
    private $command;

    /**
     * @return void
     */
    public function testHandleWithAccessDeniedException(): void
    {
        $message = $this->createMessage('unknown', 2, 'text');
        $this->bot
            ->expects($this->once())
            ->method('say')
            ->with(2, 'You has not access to bot. Please edit bundle configs to use me.', 1);

        $this->messageHandler->handle($message);
    }

    /**
     * @return void
     */
    public function testHandleWithWrongMessageException(): void
    {
        // reply command without replyToMessage
        $message = $this->createMessage('johnsnow', 2, 'reply one');
        $this->bot
            ->expects($this->once())
            ->method('say')
            ->with(2, 'Only bot replies and bot commands supported. Check privacy mode.', 1);

        $this->messageHandler->handle($message);
    }

    /**
     * @return void
     */
    public function testHandleWithUnsupportedCommandException(): void
    {
        $message = $this->createMessage('', 123, '/unknown');
        $this->bot
            ->expects($this->once())
            ->method('say')
            ->with(123, 'Unsupported command for message with text "/unknown".', 1);

        $this->messageHandler->handle($message);
    }

    /**
     * @return void
     */
    public function testHandle(): void
    {
        $message = $this->createMessage('', 123, '/start');
        /** @var MockObject $commandMock */
        $this->command->method('isMatches')->with('/start')->willReturn(true);
        $this->command->expects($this->once())->method('execute');


        $this->messageHandler->handle($message);
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->command = $this->createMock(CommandInterface::class);
        $this->command->method('getTargetText')->willReturn('/start');
        $replyCommand = $this->createMock(CommandInterface::class);
        $replyCommand->method('getTargetText')->willReturn('reply one');

        $this->bot = $this->createMock(Bot::class);
        $this->messageHandler = new MessageHandler(
            $this->bot,
            new CommandRegistry([$this->command, $replyCommand]),
            ['johndoe', 'johnsnow'],
            123
        );
    }

    /**
     * @param string $username
     * @param int    $chatId
     * @param string $text
     *
     * @return Message
     */
    private function createMessage(string $username, int $chatId, string $text): Message
    {
        return new Message(1, new DateTime(), new Chat($chatId, 'group'), new User(3, $username, false), $text);
    }
}
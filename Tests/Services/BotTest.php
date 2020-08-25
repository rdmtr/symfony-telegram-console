<?php

declare(strict_types=1);

namespace Services;

use PHPUnit\Framework\TestCase;
use Rdmtr\TelegramConsole\Services\Api\Client;
use Rdmtr\TelegramConsole\Services\Api\ReplyMarkupGenerator;
use Rdmtr\TelegramConsole\Services\Bot;

/**
 * Class BotTest
 */
class BotTest extends TestCase
{
    /**
     * @return void
     */
    public function testSay(): void
    {
        $tooLongMessage = '1';
        for ($i = 0; $i < 5000; $i++) {
            $tooLongMessage .= '1';
        }

        $client = $this->createMock(Client::class);
        $client->expects($this->exactly(2))->method('call');

        $bot = new Bot($client, new ReplyMarkupGenerator());
        $bot->say(1, $tooLongMessage);

        $client = $this->createMock(Client::class);
        $client->expects($this->once())->method('call');

        $bot = new Bot($client, new ReplyMarkupGenerator());
        $bot->say(1, 'short message');
    }
}
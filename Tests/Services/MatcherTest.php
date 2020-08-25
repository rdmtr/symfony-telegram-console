<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Tests\Services;

use Generator;
use PHPUnit\Framework\TestCase;
use Rdmtr\TelegramConsole\Services\Matcher;

class MatcherTest extends TestCase
{
    /**
     * @dataProvider getPlaceholdersProvider
     *
     * @param bool   $expectedIsMatched
     * @param array  $expectedPlaceholders
     * @param string $filledMessage
     * @param string $patternMessage
     */
    public function testGetPlaceholders(
        bool $expectedIsMatched,
        array $expectedPlaceholders,
        string $filledMessage,
        string $patternMessage
    ): void {
        $matcher = new Matcher();
        self::assertSame($expectedIsMatched, $matcher->isMatched($filledMessage, $patternMessage));
        self::assertSame($expectedPlaceholders, $matcher->getPlaceholders($filledMessage, $patternMessage));
    }

    /**
     * @return Generator
     */
    public function getPlaceholdersProvider(): Generator
    {
        yield [
            true,
            [],
            'Reply without placeholder',
            'Reply without placeholder',
        ];

        yield [
            false,
            [],
            'Lorem ipsum',
            'Reply without placeholder',
        ];

        yield [
            false,
            [],
            'Lorem ipsum',
            'Reply with {placeholder}',
        ];

        yield [
            true,
            ['one' => '1'],
            'Reply with 1 placeholder',
            'Reply with {one} placeholder',
        ];

        yield [
            true,
            ['one' => 'first', 'two' => 'second'],
            'Reply with first and second placeholder',
            'Reply with {one} and {two} placeholder',
        ];
    }
}
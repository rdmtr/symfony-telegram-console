<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Tests\Services;

use Generator;
use PHPUnit\Framework\TestCase;
use Rdmtr\TelegramConsole\Services\Matcher;

class MatcherTest extends TestCase
{
    /** @var Matcher */
    private $matcher;

    /**
     * @dataProvider getPlaceholdersProvider
     *
     * @param bool   $expectedIsMatched
     * @param array  $expectedPlaceholders
     * @param string $filledMessage
     * @param string $patternMessage
     */
    public function testMatcher(
        bool $expectedIsMatched,
        array $expectedPlaceholders,
        string $filledMessage,
        string $patternMessage
    ): void {
        self::assertSame($expectedIsMatched, $this->matcher->isMatched($filledMessage, $patternMessage));
        self::assertSame($expectedPlaceholders, $this->matcher->getPlaceholders($filledMessage, $patternMessage));
    }

    /**
     * @return Generator
     */
    public function getPlaceholdersProvider(): Generator
    {
        yield [
            true,
            [],
            '/simplecommand',
            '/simplecommand',
        ];

        yield [
            false,
            [],
            '/simplecommand',
            '/anothersimplecommand',
        ];

        yield [
            false,
            [],
            '/simplecommand',
            '/anothersimplecommand',
        ];

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
            false,
            [],
            'Lorem ipsum {placeholder} pattern',
            'Another lorem ipsum {placeholder} pattern',
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

    protected function setUp(): void
    {
        $this->matcher = new Matcher();
    }
}

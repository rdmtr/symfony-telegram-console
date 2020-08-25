<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Api\Methods;

use Rdmtr\TelegramConsole\Api\MethodInterface;

/**
 * Class SetWebhook
 */
final class SetWebhook implements MethodInterface
{
    /**
     * @var string
     */
    private $url;

    /**
     * SetWebhook constructor.
     *
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'setWebhook';
    }

    /**
     * @return string[]
     */
    public function jsonSerialize(): array
    {
        return ['url' => $this->url];
    }
}
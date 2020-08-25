<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Api;

use JsonSerializable;

/**
 * Interface MethodInterface
 */
interface MethodInterface extends JsonSerializable
{
    /** @return string */
    public function getName(): string;
}
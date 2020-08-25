<?php

declare(strict_types=1);

namespace Rdmtr\TelegramConsole\Services\Api;

/**
 * Class ReplyMarkupGenerator
 */
final class ReplyMarkupGenerator
{
    /**
     * @param array $keyboardButtons
     * @param bool  $resizeKeyboard
     * @param bool  $oneTimeKeyboard
     * @param bool  $selective
     * @param bool  $requestContact
     * @param bool  $requestLocation
     *
     * @return array
     */
    public function keyboard(
        array $keyboardButtons,
        bool $resizeKeyboard = true,
        bool $oneTimeKeyboard = true,
        bool $selective = true,
        bool $requestContact = false,
        bool $requestLocation = false
    ): array {
        $keyboard = [];
        foreach ($keyboardButtons as $keyboardButton) {
            $keyboard[] = [
                'text'             => $keyboardButton,
                'request_contact'  => $requestContact,
                'request_location' => $requestLocation,
            ];
        }

        return [
            'keyboard'          => [$keyboard],
            'resize_keyboard'   => $resizeKeyboard,
            'one_time_keyboard' => $oneTimeKeyboard,
            'selective'         => $selective,
        ];
    }

    /**
     * @param bool $selective
     *
     * @return bool[]
     */
    public function forceReply(bool $selective = true): array
    {
        return [
            'force_reply' => true,
            'selective'   => $selective,
        ];
    }
}
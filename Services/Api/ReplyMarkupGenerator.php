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
     * @param int   $countInRow
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
        int $countInRow = 2,
        bool $resizeKeyboard = true,
        bool $oneTimeKeyboard = true,
        bool $selective = true,
        bool $requestContact = false,
        bool $requestLocation = false
    ): array {
        $keyboard = [];
        $i = 1;
        foreach ($keyboardButtons as $keyboardButton) {
            $row[] = [
                'text'             => $keyboardButton,
                'request_contact'  => $requestContact,
                'request_location' => $requestLocation,
            ];

            if (0 === $i % $countInRow) {
                $keyboard[] = $row;
                $row = [];
            }
            $i++;
        }

        return [
            'keyboard'          => $keyboard,
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
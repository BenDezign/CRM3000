<?php

namespace App\Enum;

abstract class Status
{
    const STATUS_NOT_STARTED = 'not_started';
    const STATUS_PENDING = 'pending';
    const STATUS_CANCEL = 'cancel';
    const STATUS_CONFIRM = 'confirm';

    public static function getAvailableStatus(): array
    {
        return [
            self::STATUS_NOT_STARTED,
            self::STATUS_PENDING,
            self::STATUS_CANCEL,
            self::STATUS_CONFIRM,
        ];
    }

    public static function getChoices(): array
    {
        $choices = [];

        foreach (self::getAvailableStatus() as $choice) {
            $choices[$choice] = $choice;
        }

        return $choices;
    }
}
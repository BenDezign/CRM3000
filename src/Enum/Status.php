<?php

namespace App\Enum;

abstract class Status
{
    const STATUS_NOT_STARTED = 'not_started';
    const STATUS_PENDING = 'pending';
    const STATUS_CANCEL = 'cancel';
    const STATUS_CONFIRM = 'confirm';

    /** @var array user friendly named type */
    protected static $typeName = [
        self::STATUS_NOT_STARTED => 'not_started',
        self::STATUS_PENDING => 'pending',
        self::STATUS_CANCEL => 'cancel',
        self::STATUS_CONFIRM => 'confirm',
    ];


    public static function getStatusName(string $statusShortName): string
    {
        if (!isset(static::$typeName[$statusShortName])) {
            return "Unknown type ($statusShortName)";
        }

        return static::$typeName[$statusShortName];
    }

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
            $choices[static::$typeName[$choice]] = $choice;
        }

        return $choices;
    }
}
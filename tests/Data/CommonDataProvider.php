<?php

declare(strict_types=1);

namespace Tests\Data;

final class CommonDataProvider
{
    public static function iconsCollectionProvider() : array
    {
        return [
            'TFA' => [
                'tfa',
            ],
            'SELFH' => [
                'selfh',
            ],
            'DASHBOARDICONS' => [
                'dashboardicons',
            ],
        ];
    }

}

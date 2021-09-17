<?php

namespace App\Services\Dto;

class TwoFAccountDto
{
    /* @var string */
    public string $otp_type;

    /* @var string */
    public string $account = '';

    /* @var string */
    public ?string $service = null;

    /* @var string */
    public ?string $icon = null;

    /* @var string */
    public ?string $secret = null;

    /* @var string */
    public ?string $algorithm = 'sha1';

    /* @var integer */
    public ?int $digits = 6;

    /* @var integer */
    public ?int $period = 30;

    /* @var integer */
    public ?int $counter = 0;
}
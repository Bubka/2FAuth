<?php

namespace App\Services\Dto;

class OtpDto
{
    /* @var integer */
    public string $password;

    /* @var integer */
    public string $otp_type;

    /* @var integer */
    public ?int $generated_at;

    /* @var integer */
    public ?int $valid_for;

    /* @var integer */
    public ?int $counter;
}
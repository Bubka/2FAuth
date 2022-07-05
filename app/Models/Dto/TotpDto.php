<?php

namespace App\Models\Dto;

class TotpDto extends OtpDto
{
    /* @var integer */
    public int $generated_at;

    /* @var integer */
    public int $period;
}
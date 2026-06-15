<?php

namespace App\Models\Dto;

class TotpDto extends OtpDto
{
    public int $generated_at;

    public int $period;

    public string $next_password;
}

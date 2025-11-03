<?php

namespace App\Services\LogoLib;

interface LogoLibInterface
{
    public function getIcon(?string $serviceName, ?string $variant = null) : ?string;
}

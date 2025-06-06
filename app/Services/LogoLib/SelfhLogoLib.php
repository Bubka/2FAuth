<?php

namespace App\Services\LogoLib;

use App\Services\LogoLib\AbstractLogoLib;
use App\Services\LogoLib\LogoLibInterface;

class SelfhLogoLib extends AbstractLogoLib implements LogoLibInterface
{
    /**
     * The prefix to be aplied to cached filename.
     */
    protected string $cachePrefix = 'selfh_';

    /**
     * Base url of the icon collection
     */
    protected string $libUrl = 'https://cdn.jsdelivr.net/gh/selfhst/icons/';
}

<?php

namespace Tests\Classes;

use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

class LocalFile extends SymfonyUploadedFile
{

    /**
     * Begin creating a new local file fake.
     *
     * @return \Tests\Classes\LocalFileFactory
     */
    public static function fake()
    {
        return new LocalFileFactory;
    }

}

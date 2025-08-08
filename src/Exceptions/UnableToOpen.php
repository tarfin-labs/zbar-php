<?php

namespace TarfinLabs\ZbarPhp\Exceptions;

use Exception;

class UnableToOpen extends Exception
{
    /**
     * No such file exception.
     */
    public static function noSuchFile(string $file): static
    {
        return new static("Unable to open `{$file}`: No such file.");
    }
}

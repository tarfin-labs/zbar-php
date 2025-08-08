<?php

namespace TarfinLabs\ZbarPhp\Exceptions;

use Exception;

class InvalidFormat extends Exception
{
    /**
     * Invalid mime type exception.
     */
    public static function invalidMimeType(string $mimeType): static
    {
        return new static("The file type `{$mimeType}` does not valid.");
    }
}

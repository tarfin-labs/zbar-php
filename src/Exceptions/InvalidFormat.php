<?php

namespace TarfinLabs\ZbarPhp\Exceptions;

use Exception;

class InvalidFormat extends Exception
{
    /**
     * Invalid mime type exception.
     *
     * @param $mimeType
     * @return static
     */
    public static function invalidMimeType($mimeType)
    {
        return new static("The file type `{$mimeType}` does not valid.");
    }
}

<?php

namespace TarfinLabs\ZbarPhp\Exceptions;

use Exception;

class UnableToOpen extends Exception
{
    /**
     * No such file exception.
     *
     * @param $file
     * @return static
     */
    public static function noSuchFile($file)
    {
        return new static("Unable to open `{$file}`: No such file.");
    }
}

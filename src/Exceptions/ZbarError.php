<?php

namespace TarfinLabs\ZbarPhp\Exceptions;

use Exception;
use Throwable;

class ZbarError extends Exception
{
    /**
     * Zbar exit status code messages.
     *
     * @param $code
     * @return static
     */
    public static function exitStatus($code)
    {
        $message = '';

        switch ($code) {
            case 0:
                $message = 'Barcodes successfully detected in all images. Warnings may have been generated, but no errors.';
                break;
            case 1:
                $message = 'An error occurred while processing some image(s). This includes bad arguments, I/O errors and image handling errors from ImageMagick.';
                break;
            case 2:
                $message = 'ImageMagick fatal error.';
                break;
            case 3:
                $message = 'The user quit the program before all images were scanned. Only applies when running in interactive mode (with --display)';
                break;
            case 4:
                $message = 'No barcode was detected in one or more of the images. No other errors occurred.';
                break;
            default:
                $message = 'Unknown error';
                break;
        }

        return new static($message);
    }
}

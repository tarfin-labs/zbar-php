<?php

namespace TarfinLabs\ZbarPhp;

use Symfony\Component\Process\Process;
use TarfinLabs\ZbarPhp\Exceptions\InvalidFormat;
use TarfinLabs\ZbarPhp\Exceptions\UnableToOpen;
use TarfinLabs\ZbarPhp\Exceptions\ZbarError;

class Zbar
{
    /**
     * @var Process
     */
    protected $process;

    /**
     * @var Process
     */
    protected $processType;

    /**
     * Supported file formats.
     *
     * @var array
     */
    protected $validFormats = [
        'application/pdf',
        'image/png',
        'image/jpeg',
        'image/svg+xml',
        'image/gif',
    ];

    /**
     * Zbar constructor.
     *
     * @param $image
     *
     * @throws InvalidFormat
     * @throws UnableToOpen
     */
    public function __construct($image)
    {
        if (!file_exists($image)) {
            throw UnableToOpen::noSuchFile($image);
        }

        $mimeType = mime_content_type($image);

        if (!in_array($mimeType, $this->validFormats)) {
            throw InvalidFormat::invalidMimeType($mimeType);
        }

        $this->process = new Process(['zbarimg', '-q', '--raw', $image]);
        $this->processType = new Process(['zbarimg', '-q', $image]);
    }

    /**
     * Scan bar-code and return value.
     *
     * @return string
     *
     * @throws ZbarError
     */
    public function scan()
    {
        $this->process->run();

        if (!$this->process->isSuccessful()) {
            throw ZbarError::exitStatus($this->process->getExitCode());
        }

        return trim($this->process->getOutput());
    }

    /**
     * Get the bar code type after scanning it.
     *
     * @return string
     *
     * @throws ZBarError
     */
    public function type()
    {
        $this->processType->run();

        if (!$this->processType->isSuccessful()) {
            throw ZbarError::exitStatus($this->processType->getExitCode());
        }

        $parts = explode(":", $this->processType->getOutput());

        if (count($parts) !== 2 || empty($parts[0]) || !is_string($parts[0])) {
            throw ZbarError::exitStatus(5);
        }

        return $parts[0];
    }
}

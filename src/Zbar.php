<?php

namespace TarfinLabs\ZbarPhp;

use Symfony\Component\Process\Process;
use TarfinLabs\ZbarPhp\Exceptions\InvalidFormat;
use TarfinLabs\ZbarPhp\Exceptions\UnableToOpen;
use TarfinLabs\ZbarPhp\Exceptions\ZbarError;

class Zbar
{
    /**
     * @var object
     */
    protected $output;

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
     * @throws \TarfinLabs\ZbarPhp\Exceptions\ZbarError
     */
    public function __construct($image)
    {
        if (! file_exists($image)) {
            throw UnableToOpen::noSuchFile($image);
        }

        $mimeType = mime_content_type($image);

        if (! in_array($mimeType, $this->validFormats)) {
            throw InvalidFormat::invalidMimeType($mimeType);
        }

        $this->process = new Process(['zbarimg', '-q', '--xml', $image]);
        $this->runProcess();
    }

    /**
     * Run process and assign object data to output.
     *
     * @return void
     *
     * @throws \TarfinLabs\ZbarPhp\Exceptions\ZbarError
     */
    private function runProcess()
    {
        $this->process->run();

        if (! $this->process->isSuccessful()) {
            throw ZbarError::exitStatus($this->process->getExitCode());
        }

        $this->output = $this->parse($this->process->getOutput());
    }

    /**
     * Scan bar-code and return value.
     *
     * @return string
     */
    public function scan()
    {
        return $this->output->data;
    }

    /**
     * Get the bar-code type after scanning it.
     *
     * @return string
     */
    public function type()
    {
        return $this->decode()->type();
    }

    /**
     * Find both the bar-code and type of the bar-code then returns an object.
     *
     * @return BarCode
     */
    public function decode()
    {
        $code = $this->output->data;
        $type = $this->output->{'@attributes'}->type;

        return new BarCode($code, $type);
    }

    /**
     * Return symbol object.
     *
     * @param $output
     * @return object
     */
    private function parse($output)
    {
        $xml = simplexml_load_string($output, 'SimpleXMLElement', LIBXML_NOCDATA);
        $encodedOutput = json_encode($xml);
        $decodedOutput = json_decode($encodedOutput);

        return $decodedOutput->source->index->symbol;
    }
}

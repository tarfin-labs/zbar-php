<?php

namespace TarfinLabs\ZbarPhp;

use Symfony\Component\Process\Process;
use TarfinLabs\ZbarPhp\Exceptions\InvalidFormat;
use TarfinLabs\ZbarPhp\Exceptions\UnableToOpen;
use TarfinLabs\ZbarPhp\Exceptions\ZbarError;

class Zbar
{
    protected Process $process;

    protected object $output;

    /**
     * Supported file formats.
     */
    protected array $validFormats = [
        'application/pdf',
        'image/png',
        'image/jpeg',
        'image/svg+xml',
        'image/gif',
    ];

    /**
     * Zbar constructor.
     *
     * @throws InvalidFormat
     * @throws UnableToOpen
     */
    public function __construct(string $image)
    {
        if (! file_exists($image)) {
            throw UnableToOpen::noSuchFile($image);
        }

        $mimeType = mime_content_type($image);

        if (! in_array($mimeType, $this->validFormats)) {
            throw InvalidFormat::invalidMimeType($mimeType);
        }

        $this->process = new Process(['zbarimg', '-q', '--xml', $image]);
    }

    /**
     * Run process and assign object data to output.
     *
     * @throws \TarfinLabs\ZbarPhp\Exceptions\ZbarError
     */
    private function runProcess(): object
    {
        if (! empty($this->output)) {
            return $this->output;
        }

        $this->process->run();

        if (! $this->process->isSuccessful()) {
            throw ZbarError::exitStatus($this->process->getExitCode());
        }

        return $this->output = $this->parse($this->process->getOutput());
    }

    /**
     * Scan bar-code and return value.
     *
     * @throws \TarfinLabs\ZbarPhp\Exceptions\ZbarError
     */
    public function scan(): string
    {
        $output = $this->runProcess();

        return $output->data;
    }

    /**
     * Get the bar-code type after scanning it.
     *
     * @throws \TarfinLabs\ZbarPhp\Exceptions\ZbarError
     */
    public function type(): string
    {
        return $this->decode()->type();
    }

    /**
     * Find both the bar-code and type of the bar-code then returns an object.
     *
     * @throws \TarfinLabs\ZbarPhp\Exceptions\ZbarError
     */
    public function decode(): BarCode
    {
        $output = $this->runProcess();
        $code = $output->data;
        $type = $output->{'@attributes'}->type;

        return new BarCode($code, $type);
    }

    /**
     * Return symbol object.
     */
    private function parse(string $output): object
    {
        $xml = simplexml_load_string($output, 'SimpleXMLElement', LIBXML_NOCDATA);
        $encodedOutput = json_encode($xml);
        $decodedOutput = json_decode($encodedOutput);

        return $decodedOutput->source->index->symbol;
    }
}

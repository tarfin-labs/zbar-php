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
    }

    /**
     * Run process and assign object data to output.
     *
     * @return object
     *
     * @throws \TarfinLabs\ZbarPhp\Exceptions\ZbarError
     */
    private function runProcess()
    {
        if (! empty($this->output)) {
            return $this->output;
        }

        $this->process->run();

		$output = $this->process->getOutput();
		
        if (! $this->process->isSuccessful()) {
			
			if ($this->process->getExitCode() !== -1 || strpos($output, '<barcodes') !== 0)
                throw ZbarError::exitStatus($this->process->getExitCode());
        }

        return $this->output = $this->parse($output);
    }

    /**
     * Scan bar-code and return value.
     *
     * @return string
     *
     * @throws \TarfinLabs\ZbarPhp\Exceptions\ZbarError
     */
    public function scan()
    {
        $output = $this->runProcess();
		
		if (is_array($output))
			$output = $output[0];

        return $output->data;
    }

	/**
	 * Scan bar-codes and return all values.
	 *
	 * @return string[]
	 *
	 * @throws \TarfinLabs\ZbarPhp\Exceptions\ZbarError
	 */
	public function scanAll() {
		$output = $this->runProcess();

		if (!is_array($output))
			$output = [$output];

		return array_map(function ($item) {
			return $item->data;
		}, $output);
	}

    /**
     * Get the bar-code type after scanning it.
     *
     * @return string
     *
     * @throws \TarfinLabs\ZbarPhp\Exceptions\ZbarError
     */
    public function type()
    {
        return $this->decode()->type();
    }

    /**
     * Find both the bar-code and type of the bar-code then returns an object.
     *
     * @return BarCode
     *
     * @throws \TarfinLabs\ZbarPhp\Exceptions\ZbarError
     */
    public function decode()
    {
        $output = $this->runProcess();

	    if (is_array($output))
		    $output = $output[0];
		
        $code = $output->data;
        $type = $output->{'@attributes'}->type;

        return new BarCode($code, $type);
    }
	
	/**
     * Find both the bar-code and type of the bar-codes then returns objects for all barcodes.
     *
     * @return BarCode[]
     *
     * @throws \TarfinLabs\ZbarPhp\Exceptions\ZbarError
     */
    public function decodeAll()
    {
        $output = $this->runProcess();

	    if (!is_array($output))
		    $output = [$output];
		
        return array_map(function ($item) {
	        return new BarCode($item->data, $item->{'@attributes'}->type);
        }, $output);
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

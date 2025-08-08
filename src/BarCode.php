<?php

namespace TarfinLabs\ZbarPhp;

class BarCode
{
    public function __construct(protected string $code, protected string $type)
    {
    }

    /**
     * Returns the bar code.
     */
    public function code(): string
    {
        return $this->code;
    }

    /**
     * Returns the type of bar code.
     */
    public function type(): string
    {
        return $this->type;
    }
}

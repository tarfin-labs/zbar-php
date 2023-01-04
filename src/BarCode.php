<?php

namespace TarfinLabs\ZbarPhp;

class BarCode
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $type;

    public function __construct($code, $type)
    {
        $this->code = $code;
        $this->type = $type;
    }

    /**
     * Returns the bar code.
     *
     * @return string
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * Returns the type of bar code.
     *
     * @return string
     */
    public function type()
    {
        return $this->type;
    }
}

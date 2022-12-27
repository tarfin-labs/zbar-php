<?php

namespace TarfinLabs\ZbarPhp\Tests;

use PHPUnit\Framework\TestCase;
use TarfinLabs\ZbarPhp\Exceptions\InvalidFormat;
use TarfinLabs\ZbarPhp\Exceptions\UnableToOpen;
use TarfinLabs\ZbarPhp\Exceptions\ZbarError;
use TarfinLabs\ZbarPhp\Zbar;

class ZbarTest extends TestCase
{
    /**
     * @var string
     */
    protected $qrcode;

    /**
     * @var string
     */
    protected $barcode;

    /**
     * @var string
     */
    protected $invalidFile;
    /**
     * @var string
     */
    protected $emptyImage;

    /**
     * @var string
     */
    protected $ean13;

    /**
     * @var string
     */
    protected $code128;

    protected function setUp(): void
    {
        parent::setUp();

        $this->qrcode = __DIR__ . '/files/qrcode.png';
        $this->barcode = __DIR__ . '/files/barcode.gif';
        $this->invalidFile = __DIR__ . '/files/qrcode.txt';
        $this->emptyImage = __DIR__ . '/files/empty.png';
        $this->ean13 = __DIR__ . '/files/ean-13.jpg';
        $this->code128 = __DIR__ . '/files/code-128.png';
    }

    /** @test */
    public function it_will_throw_unable_to_open_exception_when_try_to_scan_non_existing_file()
    {
        $this->expectException(UnableToOpen::class);

        new Zbar('nonexisting.png');
    }

    /** @test */
    public function it_will_throw_invalid_format_exception_when_try_to_scan_invalid_file_type()
    {
        $this->expectException(InvalidFormat::class);

        new Zbar($this->invalidFile);
    }

    /** @test */
    public function it_can_scan_qrcode()
    {
        $zbar = new Zbar($this->qrcode);
        $code = $zbar->scan();

        $this->assertSame('tarfin', $code);
    }

    /** @test */
    public function it_will_throw_error_when_try_to_scan_empty_image()
    {
        $this->expectException(ZbarError::class);

        $zbar = new Zbar($this->emptyImage);
        $code = $zbar->scan();
    }

    /** @test */
    public function it_can_scan_barcode()
    {
        $zbar = new Zbar($this->barcode);
        $code = $zbar->scan();

        $this->assertSame('tarfin-1234', $code);
    }

    /** @test */
    public function it_can_get_ean13_bar_code_type()
    {
        $zbar = new Zbar($this->ean13);
        $type = $zbar->type();

        $this->assertSame('EAN-13', $type);
    }

    /** @test */
    public function it_can_get_code128_bar_code_type()
    {
        $zbar = new ZBar($this->code128);
        $type = $zbar->type();

        $this->assertSame('CODE-128', $type);
    }

    /** @test */
    public function it_can_get_bar_code_and_type_of_code128_bar_code()
    {
        $zbar = new ZBar($this->code128);
        $barCode = $zbar->decode();

        $this->assertSame('1234567890', $barCode->code());
        $this->assertSame('CODE-128', $barCode->type());
    }

    /** @test */
    public function it_can_get_bar_code_and_type_of_ean13_bar_code()
    {
        $zbar = new ZBar($this->ean13);
        $barCode = $zbar->decode();

        $this->assertSame('1234567890128', $barCode->code());
        $this->assertSame('EAN-13', $barCode->type());
    }
}

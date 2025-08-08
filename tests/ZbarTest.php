<?php

namespace TarfinLabs\ZbarPhp\Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use TarfinLabs\ZbarPhp\Exceptions\InvalidFormat;
use TarfinLabs\ZbarPhp\Exceptions\UnableToOpen;
use TarfinLabs\ZbarPhp\Exceptions\ZbarError;
use TarfinLabs\ZbarPhp\Zbar;


class ZbarTest extends TestCase
{
    protected string $qrcode;

    protected string $barcode;

    protected string $invalidFile;

    protected string $emptyImage;

    protected string $ean13;

    protected string $code128;

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

    #[Test]
    public function it_will_throw_unable_to_open_exception_when_try_to_scan_non_existing_file(): void
    {
        $this->expectException(UnableToOpen::class);

        new Zbar('nonexisting.png');
    }

    #[Test]
    public function it_will_throw_invalid_format_exception_when_try_to_scan_invalid_file_type(): void
    {
        $this->expectException(InvalidFormat::class);

        new Zbar($this->invalidFile);
    }

    #[Test]
    public function it_can_scan_qrcode(): void
    {
        $zbar = new Zbar($this->qrcode);
        $code = $zbar->scan();

        $this->assertSame('tarfin', $code);
    }

    #[Test]
    public function it_will_throw_error_when_try_to_scan_empty_image(): void
    {
        $this->expectException(ZbarError::class);

        $zbar = new Zbar($this->emptyImage);
        $code = $zbar->scan();
    }

    #[Test]
    public function it_can_scan_barcode(): void
    {
        $zbar = new Zbar($this->barcode);
        $code = $zbar->scan();

        $this->assertSame('tarfin-1234', $code);
    }

    #[Test]
    public function it_can_get_ean13_bar_code_type(): void
    {
        $zbar = new Zbar($this->ean13);
        $type = $zbar->type();

        $this->assertSame('EAN-13', $type);
    }

    #[Test]
    public function it_can_get_code128_bar_code_type(): void
    {
        $zbar = new ZBar($this->code128);
        $type = $zbar->type();

        $this->assertSame('CODE-128', $type);
    }

    #[Test]
    public function it_can_get_bar_code_and_type_of_code128_bar_code(): void
    {
        $zbar = new ZBar($this->code128);
        $barCode = $zbar->decode();

        $this->assertSame('1234567890', $barCode->code());
        $this->assertSame('CODE-128', $barCode->type());
    }

    #[Test]
    public function it_can_get_bar_code_and_type_of_ean13_bar_code(): void
    {
        $zbar = new ZBar($this->ean13);
        $barCode = $zbar->decode();

        $this->assertSame('1234567890128', $barCode->code());
        $this->assertSame('EAN-13', $barCode->type());
    }

    #[Test]
    public function it_can_get_bar_code_and_type_of_qrcode(): void
    {
        $zbar = new ZBar($this->qrcode);
        $barCode = $zbar->decode();

        $this->assertSame('tarfin', $barCode->code());
        $this->assertSame('QR-Code', $barCode->type());
    }
}

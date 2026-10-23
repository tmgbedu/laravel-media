<?php

namespace Aammui\LaravelMedia\Tests;

use Aammui\LaravelMedia\Enum\Extension;
use Aammui\LaravelMedia\Enum\Responsive;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Class ExtensionTest
 * @package Aammui\LaravelMedia\Tests
 */
class ExtensionTest extends TestCase
{
    #[Test]
    #[DataProvider('imageExtensionProvider')]
    public function it_detects_if_the_given_extension_is_image($extension)
    {
        $this->assertTrue(Extension::isImage($extension));
    }

    #[Test]
    #[DataProvider('documentExtensionProvider')]
    public function it_detects_if_the_given_extension_is_documents($extension)
    {
        $this->assertTrue(Extension::isDocument($extension));
    }

    /**
     * @return \string[][]
     */
    public static function imageExtensionProvider(): array
    {
        return [
            ["jpg"],
            ["png"],
        ];
    }

    /**
     * @return \string[][]
     */
    public static function documentExtensionProvider(): array
    {
        return [
            ["pdf"],
            ["docx"],
        ];
    }
}

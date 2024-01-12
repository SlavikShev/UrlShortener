<?php

namespace App\Tests\Unit;

use App\Service\TransformUrlService;
use PHPUnit\Framework\TestCase;

class TransformUrlServiceTest extends TestCase
{
    public function testConvertNumberToString(): void
    {
        $transformUrlService = new TransformUrlService();

        $value = $transformUrlService->encodeNumberToString(1);
        $this->assertEquals('aaaaaaa', $value);

        $value = $transformUrlService->encodeNumberToString(27);
        $this->assertEquals('aaaaaa0', $value);

        $value = $transformUrlService->encodeNumberToString(37);
        $this->assertEquals('aaaaaba', $value);

        $value = $transformUrlService->encodeNumberToString(73);
        $this->assertEquals('aaaaaca', $value);
    }

    public function testConvertStringToNumber(): void
    {
        $transformUrlService = new TransformUrlService();

        $value = $transformUrlService->decodeStringToNumber('aaaaaaa');
        $this->assertEquals(1, $value);

        $value = $transformUrlService->decodeStringToNumber('aaaaaa0');
        $this->assertEquals(27, $value);

        $value = $transformUrlService->decodeStringToNumber('aaaaaba');
        $this->assertEquals(37, $value);

        $value = $transformUrlService->decodeStringToNumber('aaaaaca');
        $this->assertEquals(73, $value);
    }
}
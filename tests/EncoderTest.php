<?php
namespace Networkteam\JsonSeq\Tests\EncoderTest;

use Networkteam\JsonSeq\Encoder;
use Networkteam\JsonSeq\StringWriter;
use PHPUnit\Framework\TestCase;

class EncoderTest extends TestCase
{

    /**
     * @test
     */
    public function writesNothingWithoutEmit(): void
    {
        $stringWriter = new StringWriter();
        new Encoder($stringWriter);

        $result = $stringWriter->getString();
        $this->assertSame('', $result, 'should have empty result');
    }

    /**
     * @test
     */
    public function multipleEmitsWriteTextSequenceEncoding(): void
    {
        $stringWriter = new StringWriter();
        $encoder = new Encoder($stringWriter);

        $encoder->emit(['foo' => 'bar']);
        $encoder->emit(['bar' => 'baz']);

        $result = $stringWriter->getString();
        $this->assertSame("\x1E{\"foo\":\"bar\"}\x0A\x1E{\"bar\":\"baz\"}\x0A", $result, 'should have two sequences');
    }
}
<?php
namespace Networkteam\JsonSeq\Tests\EncoderTest;

use Networkteam\JsonSeq\ParseError;
use Networkteam\JsonSeq\StringDecoder;
use PHPUnit\Framework\TestCase;

class StringDecoderTest extends TestCase
{

    /**
     * @test
     */
    public function decodeEmptyString(): void
    {
        $decoder = new StringDecoder();
        $results = iterator_to_array($decoder->decode(''));

        $this->assertSame([], $results, 'should have empty result');
    }

    /**
     * @test
     */
    public function decodeSingleSequence(): void
    {
        $decoder = new StringDecoder(true);
        $results = iterator_to_array($decoder->decode("\x1E{\"foo\":\"bar\"}\x0A"));

        $this->assertSame([['foo' => 'bar']], $results, 'should have single result');
    }

    /**
     * @test
     */
    public function decodeMultipleSequences(): void
    {
        $decoder = new StringDecoder(true);
        $results = iterator_to_array($decoder->decode("\x1E{\"foo\":\"bar\"}\x0A\x1E{\"bar\":\"baz\"}\x0A"));

        $this->assertSame([['foo' => 'bar'], ['bar' => 'baz']], $results, 'should have two results');
    }

    /**
     * @test
     */
    public function decodeManySequences(): void
    {
        $decoder = new StringDecoder(true);
        $results = iterator_to_array($decoder->decode(str_repeat("\x1E{\"foo\":\"bar\"}\x0A", 100)));

        $this->assertCount(100, $results, 'should have correct result count');
    }

    /**
     * @test
     */
    public function decodeWithConsecutiveRsSequences(): void
    {
        $decoder = new StringDecoder(true);
        $results = iterator_to_array($decoder->decode("\x1E\x1E\x1E{\"foo\":\"bar\"}\x0A"));

        $this->assertCount(1, $results, 'should have correct result count');
    }

    /**
     * @test
     */
    public function decodeWithErrorReturnsSingleErrorButContinues(): void
    {
        $decoder = new StringDecoder(true);
        $results = iterator_to_array($decoder->decode("\x1E{foo:\"bar\",}\x0A\x1E{\"foo\":\"bar\"}\x0A"));

        $this->assertCount(2, $results, 'should have correct result count');
        $actualError = $results[0];
        $this->assertInstanceOf(ParseError::class, $actualError, 'first result should be an error');
        $actualData = $results[1];
        $this->assertSame(['foo' => 'bar'], $actualData, 'second result should be data');
    }
}
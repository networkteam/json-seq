<?php
namespace Networkteam\JsonSeq;

class StringWriter implements WriterInterface
{

    /**
     * @var string
     */
    private $buffer = '';

    public function write(string $content): void
    {
        $this->buffer .= $content;
    }

    public function getString(): string
    {
        return $this->buffer;
    }

    public function __toString()
    {
        return $this->getString();
    }

}
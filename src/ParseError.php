<?php
namespace Networkteam\JsonSeq;

class ParseError
{

    /**
     * @var int
     */
    private $error;

    /**
     * @var string
     */
    private $message;

    public function __construct(int $error, string $message)
    {
        $this->error = $error;
        $this->message = $message;
    }

    public function getError(): int
    {
        return $this->error;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
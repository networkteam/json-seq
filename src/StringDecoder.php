<?php
namespace Networkteam\JsonSeq;

class StringDecoder
{

    const RS = "\x1E";

    /**
     * @var bool
     */
    private $jsonDecodeAssoc;

    /**
     * @var int
     */
    private $jsonDecodeDepth;

    /**
     * @var int
     */
    private $jsonDecodeOptions;

    public function __construct(bool $assoc = false, int $depth = 512, int $options = 0)
    {
        $this->jsonDecodeAssoc = $assoc;
        $this->jsonDecodeDepth = $depth;
        $this->jsonDecodeOptions = $options;
    }

    public function decode(string $content): iterable
    {
        $lastPos = 0;
        $length = strlen($content);
        while ($lastPos < $length && ($nextPos = strpos($content, self::RS, $lastPos)) !== false) {
            $lastPos = strpos($content, self::RS, $nextPos + 1);
            if ($lastPos === false) {
                $lastPos = $length;
            }

            // RFC7464 2.1 states that "Multiple consecutive RS octets do not denote empty sequence elements
            // between them and can be ignored."
            if ($lastPos === $nextPos + 1) {
                continue;
            }
            $jsonText = substr($content, $nextPos + 1, $lastPos - 1);
            $data = $this->jsonDecode($jsonText);
            yield $data;
        }
    }

    /**
     * @param string $jsonText
     * @return mixed|ParseError
     */
    private function jsonDecode(string $jsonText)
    {
        $data = json_decode($jsonText, $this->jsonDecodeAssoc, $this->jsonDecodeDepth, $this->jsonDecodeOptions);
        if ($data === null && ($jsonLastError = json_last_error()) !== JSON_ERROR_NONE) {
            // RFC7464 2.1 states that the parser should continue, so we return a ParseError here
            $data = new ParseError($jsonLastError, json_last_error_msg());
        }
        return $data;
    }
}

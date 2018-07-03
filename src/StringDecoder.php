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
        while (($nextPos = strpos($content, self::RS, $lastPos)) !== false) {
            $lastPos = strpos($content, self::RS, $nextPos + 1);
            if ($lastPos === false) {
                $lastPos = $length;
            }
            if ($lastPos === $nextPos + 1) {
                continue;
            }
            $jsonText = substr($content, $nextPos + 1, $lastPos - 1);
            $data = json_decode($jsonText, $this->jsonDecodeAssoc, $this->jsonDecodeDepth, $this->jsonDecodeOptions);
            if ($data === null) {
                $jsonLastError = json_last_error();
                if ($jsonLastError !== JSON_ERROR_NONE) {
                    yield new ParseError($jsonLastError, json_last_error_msg());
                    // RFC7464 2.1 states that the parser should continue
                    continue;
                }
            }
            yield $data;
            if ($lastPos === $length) {
                break;
            }
        }
    }
}
<?php
namespace Networkteam\JsonSeq;

class Encoder
{

    const RS = "\x1E";
    const LF = "\x0A";

    /**
     * @var WriterInterface
     */
    private $writer;

    /**
     * @var int
     */
    private $jsonEncodeOptions;

    /**
     * @var int
     */
    private $jsonEncodeDepth;

    public function __construct(WriterInterface $writer, int $options = 0, int $depth = 512)
    {
        $this->writer = $writer;
        $this->jsonEncodeOptions = $options;
        $this->jsonEncodeDepth = $depth;
    }

    public function emit($data): void
    {
        $jsonText = json_encode($data, $this->jsonEncodeOptions, $this->jsonEncodeDepth);
        $this->writer->write(self::RS . $jsonText . self::LF);
    }
}
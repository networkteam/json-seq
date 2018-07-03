<?php
namespace Networkteam\JsonSeq;

interface WriterInterface
{

    public function write(string $content): void;
}
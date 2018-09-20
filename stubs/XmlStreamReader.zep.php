<?php
declare(strict_types = 1);

namespace SixDreams\StreamReader;

/**
 * Class XmlStreamReader
 */
class XmlStreamReader implements \SixDreams\StreamReader\StreamReaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse($data, int $buffer = 1024): bool {}

    /**
     * {@inheritdoc}
     */
    public function registerCallback(?string $collectPath, string $extractPath, callable $callback): StreamReaderInterface {}

    /**
     * {@inheritdoc}
     */
    public function setOptionCallbacks(callable $optionsCallback): StreamReaderInterface {}
}

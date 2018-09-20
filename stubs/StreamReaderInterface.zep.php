<?php
declare(strict_types = 1);

namespace SixDreams\StreamReader;

/**
 * Interface StreamReaderInterface
 */
interface StreamReaderInterface
{
    /**
     * Parse selected resource.
     *
     * @param resource $data   resource of stream
     * @param int      $buffer size of buffer to read stream, default is 1024.
     *
     * @return bool
     */
    public function parse($data, $buffer = 1024);

    /**
     * Register callback for return back, collectPath can be null, if you want only one node. Else it will collect
     *  all data between opening collectPath and closing extractPath and return it via callback.
     *
     * @param string|null $collectPath path to start collect, can be empty
     * @param string      $extractPath path to extract
     * @param callable    $callback    callback with single argument
     *
     * @return StreamReaderInterface
     */
    public function registerCallback(?string $collectPath, string $extractPath, callable $callback): StreamReaderInterface;

    /**
     * Register callback to work with specific resource of parser, if supported.
     *
     * @param callable $optionsCallback callback with one argument (type: resource).
     *
     * @return StreamReaderInterface
     */
    public function setOptionCallbacks(callable $optionsCallback): StreamReaderInterface;

}

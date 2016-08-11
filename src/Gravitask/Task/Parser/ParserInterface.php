<?php

namespace Gravitask\Task\Parser;


interface ParserInterface
{
    /**
     * Parse the provided input and translate the data into a
     * usable TaskItem object.
     *
     * @param $input
     * @param array|null $flags
     * @throws \InvalidArgumentException if the provided input cannot be parsed.
     * @return \Gravitask\Task\TaskItem
     */
    public function parse($input, $flags = null);
}
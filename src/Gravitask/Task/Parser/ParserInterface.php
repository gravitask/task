<?php

namespace Gravitask\Task\Parser;


interface ParserInterface
{
    /**
     * Parse the provided input and translate the data into a
     * usable TaskItem object.
     *
     * @param $input
     * @return \Gravitask\Task\TaskItem
     */
    public function parse($input);
}
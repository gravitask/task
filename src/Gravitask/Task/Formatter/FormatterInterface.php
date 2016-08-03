<?php

namespace Gravitask\Task\Formatter;

interface FormatterInterface
{
    /**
     * Format the provided TaskItem into a reusable format.
     *
     * @param \Gravitask\Task\TaskItem $taskItem
     * @param array|null $flags
     * @return string
     */
    public function format(\Gravitask\Task\TaskItem $taskItem, $flags = null);
}
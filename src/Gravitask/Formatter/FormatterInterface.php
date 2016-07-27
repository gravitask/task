<?php

namespace Gravitask\Formatter;

interface FormatterInterface
{
    /**
     * Format the provided TaskItem into a reusable format.
     *
     * @param \Gravitask\TaskItem $taskItem
     * @return string
     */
    public function format(\Gravitask\TaskItem $taskItem);
}
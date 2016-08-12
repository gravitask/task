<?php

namespace Gravitask\Task\Formatter;

use Gravitask\Task\TaskItem;

class TodoTxtFormatter extends BaseFormatter implements FormatterInterface
{
    /**
     * Format the TaskItem into a readable todo.txt format.
     *
     * @param TaskItem $taskItem
     * @param array|null $flags A list of formatter flags or null to use pre-set flags.
     * @return string
     */
    public function format(TaskItem $taskItem, $flags = null) {
        if($flags !== null) {
            $this->setFlags($flags);
        }

        $outputPieces = [];

        if($this->itemIsCompleted($taskItem)) {
            $outputPieces[] = "x";
            $outputPieces[] = $taskItem->getCompletionDate()->format("Y-m-d");
        }

        if($taskItem->getPriority() !== null) {
            $outputPieces[] = "(" . $taskItem->getPriority() . ")";
        }

        if($taskItem->getCreationDate() !== null) {
            $outputPieces[] = $taskItem->getCreationDate()->format("Y-m-d");
        }

        if($taskItem->getTask() !== null) {
            $outputPieces[] = $taskItem->getTask();
        }

        // Prevent duplicate contexts and projects being added
        foreach($taskItem->getContexts() as $context) {
            if(strpos($taskItem->getTask(), "@" . $context) === false) {
                $outputPieces[] = "@" . $context;
            }
        }

        foreach($taskItem->getProjects() as $project) {
            if(strpos($taskItem->getTask(), "+" . $project) === false) {
                $outputPieces[] = "+" . $project;
            }
        }

        return implode(" ", $outputPieces);
    }

    /**
     * Convenience method to determine whether the TaskItem is completed.
     *
     * @param TaskItem $taskItem
     * @return bool
     */
    private function itemIsCompleted(TaskItem $taskItem) {
        if(
            $taskItem->getStatus() === TaskItem::STATUS_COMPLETED &&
            $taskItem->getCompletionDate() !== null
        ) {
            return true;
        }

        return false;
    }
}
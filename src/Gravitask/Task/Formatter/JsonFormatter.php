<?php

namespace Gravitask\Task\Formatter;

use Gravitask\Task\TaskItem;

class JsonFormatter implements FormatterInterface
{
    const KEY_COMPLETED = "completed";
    const KEY_COMPLETION_DATE = "dateCompleted";
    const KEY_CREATION_DATE = "dateCreated";
    const KEY_PRIORITY = "priority";
    const KEY_TASK = "task";
    const KEY_CONTEXTS = "contexts";
    const KEY_PROJECTS = "projects";

    public function format(TaskItem $taskItem)
    {
        $output = [];

        if(
            $taskItem->getStatus() === TaskItem::STATUS_COMPLETED &&
            $taskItem->getCompletionDate() !== null
        ) {
            $output[self::KEY_COMPLETED] = true;
            $output[self::KEY_COMPLETION_DATE] = $taskItem->getCompletionDate();
        }

        if($taskItem->getPriority() !== null) {
            $output[self::KEY_PRIORITY] = $taskItem->getPriority();
        }

        if($taskItem->getCreationDate() !== null) {
            $output[self::KEY_CREATION_DATE] = $taskItem->getCreationDate();
        }

        if($taskItem->getTask() !== null) {
            $output[self::KEY_TASK] = $taskItem->getTask();
        }

        if(count($taskItem->getContexts()) > 0) {
            $output[self::KEY_CONTEXTS] = [];
            foreach($taskItem->getContexts() as $context) {
                $output[self::KEY_CONTEXTS][] = $context;
            }
        }

        if(count($taskItem->getProjects()) > 0) {
            $output[self::KEY_PROJECTS] = [];
            foreach($taskItem->getProjects() as $project) {
                $output[self::KEY_PROJECTS][] = $project;
            }
        }

        return json_encode($output);
    }
}
<?php

namespace Gravitask\Task\Formatter;

use Gravitask\Task\TaskItem;

class JsonFormatter extends BaseFormatter implements FormatterInterface
{
    /** JSON key to identify whether the task has been completed. */
    const KEY_COMPLETED = "completed";

    /** JSON key to store the date that the task was completed. */
    const KEY_COMPLETION_DATE = "dateCompleted";

    /** JSON key to store the date that the task was created. */
    const KEY_CREATION_DATE = "dateCreated";

    /** JSON key to store the task's priority. */
    const KEY_PRIORITY = "priority";

    /** JSON key to store the task's name/description. */
    const KEY_TASK = "task";

    /** JSON key to store an array of the task's contexts. */
    const KEY_CONTEXTS = "contexts";

    /** JSON key to store an array of the task's projects. */
    const KEY_PROJECTS = "projects";



    /**
     * Format the TaskItem into a JSON encoded string.
     *
     * @param TaskItem $taskItem
     * @param array|null $flags A list of formatter flags or null to use pre-set flags.
     * @return string
     */
    public function format(TaskItem $taskItem, $flags = null) {
        if($flags !== null) {
            $this->setFlags($flags);
        }

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
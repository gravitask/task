<?php

namespace Gravitask\Task\Parser;

use Gravitask\Task\TaskItem;
use Gravitask\Task\Formatter\JsonFormatter;

class JsonParser extends BaseParser implements ParserInterface
{
    /**
     * Parse the provided JSON input string into a TaskItem object.
     *
     * @param $input
     * @param array|null $flags A list of parse flags or null to use pre-set flags.
     * @throws \InvalidArgumentException if the provided JSON input cannot be parsed.
     * @return TaskItem|bool A new TaskItem object, or false on parse failure/error.
     */
    public function parse($input, $flags = null) {
        if($flags !== null) {
            $this->setFlags($flags);
        }

        $json = json_decode($input, true);

        if($json === null) {
            throw new \InvalidArgumentException("The provided JSON data is invalid.");
        }

        $taskItem = new TaskItem();

        $taskItem->setStatus($this->parseCompleted($json));
        $taskItem->setCompletionDate($this->parseCompletionDate($json));
        $taskItem->setPriority($this->parsePriority($json));
        $taskItem->setCreationDate($this->parseCreationDate($json));
        $taskItem->setTask($this->parseTask($json));
        $taskItem->setContexts($this->parseContexts($json));
        $taskItem->setProjects($this->parseProjects($json));

        return $taskItem;
    }

    /**
     * Parse whether the task is marked as completed.
     *
     * @param array $json Array representation of the input JSON string.
     * @see TaskItem::STATUS_COMPLETED
     * @return int|null ENUM of status of TaskItem.
     */
    private function parseCompleted($json) {
        if(!isset($json[JsonFormatter::KEY_COMPLETED])) { return null; }

        if($json[JsonFormatter::KEY_COMPLETED] === true) {
            return TaskItem::STATUS_COMPLETED;
        }

        return null;
    }

    /**
     * Parse the date that the task was completed (if available).
     *
     * @param array $json Array representation of the input JSON string.
     * @return string|null YYYY-MM-DD formatted date, or null.
     */
    private function parseCompletionDate($json) {
        if(!isset($json[JsonFormatter::KEY_COMPLETION_DATE])) { return null; }

        $completionDate = \DateTime::createFromFormat(\DateTime::ATOM, $json[JsonFormatter::KEY_COMPLETION_DATE]);
        if($completionDate !== false) {
            return $completionDate;
        }

        return null;
    }

    /**
     * Parse the task's priority.
     *
     * @param array $json Array representation of the input JSON string.
     * @return string|null
     */
    private function parsePriority($json) {
        if(!isset($json[JsonFormatter::KEY_PRIORITY])) { return null; }

        if(preg_match('/^[A-Z]$/', $json[JsonFormatter::KEY_PRIORITY]) === 1) {
            return $json[JsonFormatter::KEY_PRIORITY];
        }

        return null;
    }

    /**
     * Parse the optional date value of when the task was created.
     *
     * @param array $json Array representation of the input JSON string.
     * @return string|null YYYY-MM-DD formatted date, or null.
     */
    private function parseCreationDate($json) {
        if(!isset($json[JsonFormatter::KEY_CREATION_DATE])) { return null; }

        $creationDate = \DateTime::createFromFormat(\DateTime::ATOM, $json[JsonFormatter::KEY_CREATION_DATE]);
        if($creationDate !== false) {
            return $creationDate;
        }

        return null;
    }

    /**
     * Parse the task's name/description.
     *
     * @param array $json Array representation of the input JSON string.
     * @return string|null
     */
    private function parseTask($json) {
        if(!isset($json[JsonFormatter::KEY_TASK])) { return null; }

        if(is_string($json[JsonFormatter::KEY_TASK])) {
            return $json[JsonFormatter::KEY_TASK];
        }

        return null;
    }

    /**
     * Parse the array of contexts provided in the input JSON.
     *
     * @param array $json Array representation of the input JSON string.
     * @return array
     */
    private function parseContexts($json) {
        if(!isset($json[JsonFormatter::KEY_CONTEXTS]) || !is_array($json[JsonFormatter::KEY_CONTEXTS])) { return []; }

        $contexts = [];

        foreach($json[JsonFormatter::KEY_CONTEXTS] as $context) {
            if(is_string($context)) {
                $contexts[] = $context;
            }
        }

        return $contexts;
    }

    /**
     * Parse the array of projects provided in the input JSON.
     *
     * @param array $json Array representation of the input JSON string.
     * @return array
     */
    private function parseProjects($json) {
        if(!isset($json[JsonFormatter::KEY_PROJECTS]) || !is_array($json[JsonFormatter::KEY_PROJECTS])) { return []; }

        $projects = [];

        foreach($json[JsonFormatter::KEY_PROJECTS] as $project) {
            if(is_string($project)) {
                $projects[] = $project;
            }
        }

        return $projects;
    }
}
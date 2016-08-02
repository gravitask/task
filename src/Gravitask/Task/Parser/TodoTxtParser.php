<?php

namespace Gravitask\Task\Parser;


use Gravitask\Task\TaskItem;

class TodoTxtParser implements ParserInterface
{
    /** @var array Array containing parsed metadata from the input string. */
    private $parsedMetadata = [];


    /**
     * Parse the provided input and translate into a TaskItem object.
     *
     * @param string $input A raw Todo.txt formatted string.
     * @param array $flags A list of parse flags.
     * @return \Gravitask\Task\TaskItem
     */
    public function parse($input, $flags = []) {
        $newTaskItem = new TaskItem();
        $splitInput = explode(" ", $input);

        $completionData = $this->parseCompletionStatus($splitInput);
        if($completionData !== false) {
            $newTaskItem->setStatus($completionData['status']);
            $newTaskItem->setCompletionDate($completionData['date']);
        }
        $newTaskItem->setPriority($this->parsePriority($splitInput));
        $newTaskItem->setCreationDate($this->parseCreationDate($splitInput));
        $newTaskItem->setContexts($this->parseContexts($splitInput));
        $newTaskItem->setProjects($this->parseProjects($splitInput));
        $newTaskItem->setTask($this->parseTaskDescription($splitInput));

        $this->parseMetadata($splitInput);
        $newTaskItem = $this->applyMetadata($newTaskItem);

        return $newTaskItem;
    }

    /**
     * Attempt to parse the completion status and date from the input.
     *
     * @param array &$splitInput An exploded array (delimited by space) of the input data.
     * @see Gravitask\TaskItem::STATUS_ACTIVE
     * @see Gravitask\TaskItem::STATUS_COMPLETED
     * @return array|bool Array containing `date`, `status` values or false on failure.
     */
    private function parseCompletionStatus(&$splitInput) {
        if($splitInput[0] !== 'x') { return false; }

        if(preg_match('/^[0-9]{4,}\-[0-9]{2,}\-[0-9]{2,}$/', $splitInput[1]) === 1) {
            $returnData['status'] = TaskItem::STATUS_COMPLETED;
            $returnData['date'] = $splitInput[1];
            $splitInput = array_slice($splitInput, 2);
            return $returnData;
        }

        return false;
    }

    /**
     * Attempt to parse the priority of the input data.
     *
     * @param array &$splitInput An exploded array (delimited by space) of the input data.
     * @return string|null
     */
    private function parsePriority(&$splitInput) {
        if(preg_match('/^\([A-Z]\)$/', $splitInput[0]) === 1) {
            $priority = substr($splitInput[0], 1, 1);
            $splitInput = array_slice($splitInput, 1);
            return $priority;
        }

        return null;
    }

    /**
     * Attempt to parse the contexts provided in the input
     *
     * @param array $splitInput An exploded array (delimited by space) of the input data.
     * @return array
     */
    private function parseContexts($splitInput) {
        $contextsList = array();

        foreach($splitInput as $inputElement) {
            if(substr($inputElement, 0, 1) === "@") {
                array_push($contextsList, substr($inputElement, 1));
            }
        }

        return $contextsList;
    }

    /**
     * Attempt to parse the projects provided in the input
     *
     * @param array $splitInput An exploded array (delimited by space) of the input data.
     * @return array
     */
    private function parseProjects($splitInput) {
        $projectsList = array();

        foreach($splitInput as $inputElement) {
            if(substr($inputElement, 0, 1) === "+") {
                array_push($projectsList, substr($inputElement, 1));
            }
        }

        return $projectsList;
    }

    /**
     * Attempt to parse the **optional** creation date in the input.
     *
     * @param array &$splitInput An exploded array (delimited by space) of the input data.
     * @return string|null
     */
    private function parseCreationDate(&$splitInput) {
        if(preg_match('/^[0-9]{4,}\-[0-9]{2,}\-[0-9]{2,}$/', $splitInput[0]) === 1) {
            $creationDate = $splitInput[0];
            $splitInput = array_slice($splitInput, 1);
            return $creationDate;
        }

        return null;
    }

    /**
     * Attempt to parse a readable task description from the input.
     *
     * @param array $splitInput An exploded array (delimited by space) of the input data.
     * @return string
     */
    private function parseTaskDescription($splitInput) {
       return implode(" ", $splitInput);
    }

    /**
     * Search for metadata (key:value) elements in the input pieces and
     * attempt to parse them.
     *
     * @param array $splitInput An exploded array (delimited by space) of the input data.
     */
    private function parseMetadata($splitInput) {
        foreach($splitInput as $inputElement) {
            /*
             * Check the input element follows the rules:
             * - Must only have a single colon
             * - Must not contain whitespace or colons in the key or value section
             */
            if(preg_match('/([^\s|\:]+)\:([^\s|\:]+)/', $inputElement) === 1) {
                $splitElement = explode(":", $inputElement);
                $this->setParsedMetadata($splitElement[0], $splitElement[1]);
            }
        }
    }

    /**
     * Set a key:value element to apply to the TaskItem after parsing.
     *
     * This allows support for key:value items in the todo.txt format,
     * e.g. "pri:A" to set the priority, or "DUE:2016-12-31" to set a
     * due date.
     *
     * @param string $key
     * @param string $value
     */
    private function setParsedMetadata($key, $value) {
        $this->parsedMetadata[$key] = $value;
    }

    /**
     * Apply the task metadata to the TaskItem.
     *
     * @param TaskItem $taskItem
     * @return TaskItem
     */
    private function applyMetadata($taskItem) {
        foreach($this->parsedMetadata as $key => $value) {
            switch(strtoupper($key)) {
                case "PRI":
                    if(preg_match('/^[A-Z]$/', $value) === 1) {
                        $taskItem->setPriority($value);
                    }
                break;
                case "DUE":
                    if(preg_match('/^[0-9]{4,}\-[0-9]{2,}\-[0-9]{2,}$/', $value) === 1) {
                        $taskItem->addMetadata("DUE", $value);
                    }
                break;
            }
        }

        return $taskItem;
    }
}
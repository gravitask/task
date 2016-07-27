<?php

namespace Gravitask\Tools;


use Gravitask\TaskItem;

class Parser
{
    /**
     * Parse the provided input and translate into a TaskItem object.
     *
     * @param string $input A raw Todo.txt formatted string.
     * @return \Gravitask\TaskItem
     */
    public function parse($input) {
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
}
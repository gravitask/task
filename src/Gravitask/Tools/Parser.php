<?php

namespace Gravitask\Tools;


use Gravitask\TaskItem;

class Parser
{
    /** @var array A list of input indexes to identify certain data elements, e.g. priority. */
    private $indexes = array();


    /**
     * Add an index for later use.
     *
     * This is used to store the offset locations for certain data elements
     * in the split input array.
     * This allows us to perform tasks such as removing all text before the
     * priority value in order to retrieve a readable task description.
     *
     * @param string $key The name of the index, e.g. "STATUS".
     * @param int $value The array offset of the split input.
     */
    private function addIndex($key, $value) {
        $this->indexes[$key] = $value;
    }

    /**
     * Retrieve an offset value for the provided index name.
     *
     * @param string $key The name of the index.
     * @return int|null The splitInput offset value.
     */
    private function getIndex($key) {
        if(isset($this->indexes[$key])) {
            return $this->indexes[$key];
        }
        return null;
    }

    /**
     * Parse the provided input and translate into a TaskItem object.
     *
     * @param string $input A raw Todo.txt formatted string.
     * @return \Gravitask\TaskItem
     */
    public function parse($input) {
        $newTaskItem = new TaskItem();
        $splitInput = explode(" ", $input);

        $newTaskItem->setPriority($this->parsePriority($splitInput));
        $newTaskItem->setContexts($this->parseContexts($splitInput));
        $newTaskItem->setProjects($this->parseProjects($splitInput));
        $newTaskItem->setStatus($this->parseStatus($splitInput));
        $newTaskItem->setCreationDate($this->parseCreationDate($splitInput));
        $newTaskItem->setCompletionDate($this->parseCompletionDate($input));
        $newTaskItem->setTask($this->parseTaskDescription($input));

        return $newTaskItem;
    }

    /**
     * Attempt to parse the priority of the input data.
     *
     * @param array $splitInput An exploded array (delimited by space) of the input data.
     * @return string|null
     */
    private function parsePriority($splitInput) {
        foreach($splitInput as $index => $value) {
            if(preg_match('/^\([A-Z]\)$/', $value) === 1) {
                $this->addIndex("PRIORITY", $index);
                return substr($value, 1, 1);
            }
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
     * @param array $splitInput An exploded array (delimited by space) of the input data.
     * @return string|null
     */
    private function parseCreationDate($splitInput) {
        for($i = 0; $i < count($splitInput); $i++) {
            if(preg_match('/^[0-9]{4,}\-[0-9]{2,}\-[0-9]{2,}$/', $splitInput[$i]) === 1) {
                // If the previous element is an "x" then the next date item is the completion date
                if($i > 0 && $splitInput[$i - 1] === "x") { continue; }
                $this->addIndex("CREATION", $i);
                return $splitInput[$i];
            }
        }

        return null;
    }

    /**
     * Parse the task's current status.
     *
     * @param array $splitInput An exploded array (delimited by space) of the input data.
     * @see Gravitask\TaskItem::STATUS_ACTIVE
     * @see Gravitask\TaskItem::STATUS_COMPLETED
     * @return int ENUM representation of the task's status.
     */
    private function parseStatus($splitInput) {
        if($splitInput[0] === "x") {
            return TaskItem::STATUS_COMPLETED;
        }

        return TaskItem::STATUS_ACTIVE;
    }

    /**
     * Attempt to parse the completion date from the input string.
     *
     * @param string $input The raw input string in todo.txt format.
     * @return string|null
     */
    private function parseCompletionDate($input) {
        $splitInput = explode(" ", $input);

        // Task is not completed, thus it will not have a completion date
       if($this->parseStatus($input) !== TaskItem::STATUS_COMPLETED) { return null; }

        for($i = 0; $i < 4; $i++) {
            if(preg_match('/^[0-9]{4,}\-[0-9]{2,}\-[0-9]{2,}$/', $splitInput[$i]) === 1) {
                $this->addIndex("COMPLETION", $i);
                return $splitInput[$i];
            }
        }

        return null;
    }

    /**
     * Attempt to parse a readable task description from the raw input.
     *
     * @param string $input The raw input string in todo.txt format.
     * @return string
     */
    private function parseTaskDescription($input) {
        $splitInput = explode(" ", $input);

        if($this->getIndex("CREATION") !== null) {
            $elements = array_slice($splitInput, $this->getIndex("CREATION") + 1);
            return implode(" ", $elements);
        }

        if($this->getIndex("PRIORITY") !== null) {
            $elements = array_slice($splitInput, $this->getIndex("PRIORITY") + 1);
            return implode(" ", $elements);
        }

        if($this->getIndex("COMPLETION") !== null) {
            $elements = array_slice($splitInput, $this->getIndex("COMPLETION") + 1);
            return implode(" ", $elements);
        }

        if($this->parseStatus($input) === TaskItem::STATUS_COMPLETED) {
            $elements = array_slice($splitInput, 1);
            return implode(" ", $elements);
        }

        return $input;
    }
}
<?php

namespace Gravitask\Task;


class TaskItem
{
    /** Represent the task status as active/being worked on. */
    const STATUS_ACTIVE = 1;

    /** Represent the task status as completed. */
    const STATUS_COMPLETED = 2;



    /** @var string The task name/description. */
    private $task;

    /** @var array An array of the item's contexts. */
    private $contexts = [];

    /** @var array An array of the item's projects. */
    private $projects = [];

    /** @var string The creation datetime of the task in ISO8601 format. */
    private $creationDate;

    /** @var string The completion datetime of the task in ISO8601 format. */
    private $completionDate;

    /** @var string The priority of the task as a single letter (A-Z). */
    private $priority;

    /** @var int An ENUM value representing the task's status (e.g. completed) */
    private $status;

    /** @var array An array of the item's metadata (e.g. due date). */
    private $metadata = [];


    /**
     * Set the task name/description.
     *
     * @param string $task The name/description for the task.
     */
    public function setTask($task) {
        $this->task = $task;
    }

    /**
     * Get the task name/description.
     *
     * @return string
     */
    public function getTask() {
        return $this->task;
    }


    /**
     * Set a list of context values for the task.
     *
     * @param array $contexts An array containing a list of context values.
     */
    public function setContexts(array $contexts) {
        $this->contexts = $contexts;
    }

    /**
     * Add a single context item to the list of contexts.
     *
     * @param string $context The new context name.
     */
    public function addContext($context) {
        $contextsList = $this->getContexts();
        if(in_array($context, $contextsList) === false) {
            $contextsList[] = $context;
            $this->setContexts($contextsList);
        }
    }

    /**
     * Get a list of context values assigned to this task.
     *
     * @return array
     */
    public function getContexts() {
        return $this->contexts;
    }

    /**
     * Set a list of project values for the task.
     *
     * @param array $projects
     */
    public function setProjects(array $projects) {
        $this->projects = $projects;
    }

    /**
     * Add a single project to the list of projects.
     *
     * @param string $project The new project name.
     */
    public function addProject($project) {
        $projectList = $this->getProjects();
        if(in_array($project, $projectList) === false) {
            $projectList[] = $project;
            $this->setProjects($projectList);
        }
    }

    /**
     * Get a list of projects assigned to this task.
     *
     * @return array
     */
    public function getProjects() {
        return $this->projects;
    }


    /**
     * Set the creation date for the task.
     *
     * @param \DateTime|null $date
     */
    public function setCreationDate($date) {
        if($date === null) {
            $this->creationDate = null;
            return;
        }
        $this->creationDate = $date->format(\DateTime::ATOM);
    }

    /**
     * Get the creation date for the task as a DateTime object.
     *
     * @return \DateTime|null
     */
    public function getCreationDate() {
        if($this->creationDate !== null) {
            return \DateTime::createFromFormat(\DateTime::ATOM, $this->creationDate);
        }
        return null;
    }


    /**
     * Set the date that this task was completed.
     *
     * @param \DateTime $date
     */
    public function setCompletionDate($date) {
        if($date === null) {
            $this->completionDate = null;
            return;
        }
        $this->completionDate = $date->format(\DateTime::ATOM);
    }

    /**
     * Get the date of when the task was completed as a DateTime object.
     *
     * @return \DateTime|null
     */
    public function getCompletionDate() {
        if($this->completionDate !== null) {
            return \DateTime::createFromFormat(\DateTime::ATOM, $this->completionDate);
        }
        return null;
    }


    /**
     * Set the task's priority.
     *
     * @param string $priority A single letter (A-Z) with A being highest, Z being lowest.
     */
    public function setPriority($priority) {
        $this->priority = $priority;
    }

    /**
     * Get the task's priority.
     *
     * @return string
     */
    public function getPriority() {
        return $this->priority;
    }


    /**
     * Set the task's status value.
     *
     * @see TaskItem::STATUS_ACTIVE
     * @see TaskItem::STATUS_COMPLETED
     * @param $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * Get the task's status (e.g. active, completed).
     *
     * @see TaskItem::STATUS_ACTIVE
     * @see TaskItem::STATUS_COMPLETED
     * @return int
     */
    public function getStatus() {
        if($this->status === null) {
            return self::STATUS_ACTIVE;
        }
        return $this->status;
    }

    /**
     * Set a list of metadata for the task.
     *
     * @param array $metadata
     */
    public function setMetadata(array $metadata) {
        $this->metadata = $metadata;
    }

    /**
     * Add a single metadata item to the list of task metadata.
     *
     * @param string $key The metadata key name
     * @param string $value The metadata value
     */
    public function addMetadata($key, $value) {
        $metadataList = $this->getMetadata();
        if(isset($metadataList[$key]) === false) {
            $metadataList[$key] = $value;
            $this->setMetadata($metadataList);
        }
    }

    /**
     * Get a list of metadata assigned to this task.
     *
     * @return array
     */
    public function getMetadata() {
        return $this->metadata;
    }
}
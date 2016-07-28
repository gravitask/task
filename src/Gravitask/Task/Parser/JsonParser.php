<?php

namespace Gravitask\Task\Parser;

use Gravitask\Task\TaskItem;

class JsonParser implements ParserInterface
{
    public function parse($input)
    {
        $json = json_decode($input, true);

        if($json === null) {
            return false;
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

    private function parseCompleted($json) {
        if(!isset($json['completed'])) { return null; }

        if($json['completed'] === true) {
            return TaskItem::STATUS_COMPLETED;
        }

        return null;
    }

    private function parseCompletionDate($json) {
        if(!isset($json['dateCompleted'])) { return null; }

        if(preg_match('/^[0-9]{4,}\-[0-9]{2,}\-[0-9]{2,}$/', $json['dateCompleted'])) {
            return $json['dateCompleted'];
        }

        return null;
    }

    private function parsePriority($json) {
        if(!isset($json['priority'])) { return null; }

        if(preg_match('/^[A-Z]$/', $json['priority']) === 1) {
            return $json['priority'];
        }

        return null;
    }

    private function parseCreationDate($json) {
        if(!isset($json['dateCreated'])) { return null; }

        if(preg_match('/^[0-9]{4,}\-[0-9]{2,}\-[0-9]{2,}$/', $json['dateCreated'])) {
            return $json['dateCreated'];
        }

        return null;
    }

    private function parseTask($json) {
        if(!isset($json['task'])) { return null; }

        if(is_string($json['task'])) {
            return $json['task'];
        }

        return null;
    }

    private function parseContexts($json) {
        if(!isset($json['contexts']) || !is_array($json['contexts'])) { return []; }

        $contexts = [];

        foreach($json['contexts'] as $context) {
            if(is_string($context)) {
                $contexts[] = $context;
            }
        }

        return $contexts;
    }

    private function parseProjects($json) {
        if(!isset($json['projects']) || !is_array($json['projects'])) { return []; }

        $projects = [];

        foreach($json['projects'] as $project) {
            if(is_string($project)) {
                $projects[] = $project;
            }
        }

        return $projects;
    }
}
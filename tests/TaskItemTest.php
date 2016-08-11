<?php

require __DIR__ . '/../vendor/autoload.php';

class TaskItemTest extends PHPUnit_Framework_TestCase
{
    public function testSetters() {
        $taskItem = new Gravitask\Task\TaskItem();
        $taskItem->setTask('A simple task');
        $taskItem->setContexts(['One', 'Two']);
        $taskItem->addContext('Three');
        $taskItem->setProjects(['Secret']);
        $taskItem->addProject('ProjectDeux');
        $taskItem->setCreationDate('2010-05-03');
        $taskItem->setCompletionDate('2010-06-11');
        $taskItem->setPriority('C');
        $taskItem->setStatus(\Gravitask\Task\TaskItem::STATUS_COMPLETED);
        $taskItem->setMetadata(['Key' => 'Value']);
        $taskItem->addMetadata('Second', 'Value #2');

        $this->assertEquals('A simple task', $taskItem->getTask());
        $this->assertEquals(['One', 'Two', 'Three'], $taskItem->getContexts());
        $this->assertEquals(['Secret', 'ProjectDeux'], $taskItem->getProjects());
        $this->assertEquals('2010-05-03', $taskItem->getCreationDate());
        $this->assertEquals('2010-06-11', $taskItem->getCompletionDate());
        $this->assertEquals('C', $taskItem->getPriority());
        $this->assertEquals(\Gravitask\Task\TaskItem::STATUS_COMPLETED, $taskItem->getStatus());
        $this->assertEquals(['Key' => 'Value', 'Second' => 'Value #2'], $taskItem->getMetadata());
    }
}

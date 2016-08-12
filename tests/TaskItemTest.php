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
        $taskItem->setCreationDate(\DateTime::createFromFormat('Y-m-d', '2010-05-03'));
        $taskItem->setCompletionDate(\DateTime::createFromFormat('Y-m-d', '2010-06-11'));
        $taskItem->setPriority('C');
        $taskItem->setStatus(\Gravitask\Task\TaskItem::STATUS_COMPLETED);
        $taskItem->setMetadata(['Key' => 'Value']);
        $taskItem->addMetadata('Second', 'Value #2');

        $this->assertEquals('A simple task', $taskItem->getTask());
        $this->assertEquals(['One', 'Two', 'Three'], $taskItem->getContexts());
        $this->assertEquals(['Secret', 'ProjectDeux'], $taskItem->getProjects());
        $this->assertEquals('2010-05-03', $taskItem->getCreationDate()->format('Y-m-d'));
        $this->assertEquals('2010-06-11', $taskItem->getCompletionDate()->format('Y-m-d'));
        $this->assertEquals('C', $taskItem->getPriority());
        $this->assertEquals(\Gravitask\Task\TaskItem::STATUS_COMPLETED, $taskItem->getStatus());
        $this->assertEquals(['Key' => 'Value', 'Second' => 'Value #2'], $taskItem->getMetadata());
    }

    public function testSetCreationDateTime() {
        $taskItem = new Gravitask\Task\TaskItem();
        $taskItem->setTask('Test Creation DateTime');

        $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-08-11 08:00:01');
        $taskItem->setCreationDate($dateTime);

        $this->assertEquals('2016-08-11 08:00:01', $taskItem->getCreationDate()->format('Y-m-d H:i:s'));
    }

    public function testSetCompletionDateTime() {
        $taskItem = new Gravitask\Task\TaskItem();
        $taskItem->setTask('Test Creation DateTime');

        $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', '2016-08-11 12:34:17');
        $taskItem->setCreationDate($dateTime);

        $this->assertEquals('2016-08-11 12:34:17', $taskItem->getCreationDate()->format('Y-m-d H:i:s'));
    }
}

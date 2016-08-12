<?php

require __DIR__ . '/../../vendor/autoload.php';

class JsonFormatterTest extends PHPUnit_Framework_TestCase
{
    /** @var \Gravitask\Task\Formatter\FormatterInterface */
    private $formatter;

    public function setUp() {
        $this->formatter = new \Gravitask\Task\Formatter\JsonFormatter();
    }

    public function testBasic() {
        $task = new Gravitask\Task\TaskItem();
        $task->setTask("Test JSON");
        $task->setPriority("A");

        $jsonOutput = $this->formatter->format($task);

        $expected = '{"priority":"A","task":"Test JSON"}';

        $this->assertEquals($expected, $jsonOutput);
    }

    public function testCompletedTask() {
        $task = new Gravitask\Task\TaskItem();
        $task->setStatus(Gravitask\Task\TaskItem::STATUS_COMPLETED);
        $task->setCompletionDate(\DateTime::createFromFormat("Y-m-d H:i:s", "2016-11-12 07:00:45"));
        $task->setPriority("B");
        $task->setCreationDate(\DateTime::createFromFormat("Y-m-d H:i:s", "2016-11-11 09:45:10"));
        $task->setTask("Finished Task");
        $task->setContexts(["email"]);
        $task->setProjects(["exampleProject"]);

        $jsonOutput = $this->formatter->format($task);
        $decodedJson = json_decode($jsonOutput, true);

        $this->assertEquals(true, $decodedJson['completed']);
        $completionDate = \DateTime::createFromFormat(\DateTime::ATOM, $decodedJson['dateCompleted']);
        $this->assertEquals("2016-11-12 07:00:45", $completionDate->format('Y-m-d H:i:s'));
        $this->assertEquals("B", $decodedJson['priority']);
        $creationDate = \DateTime::createFromFormat(\DateTime::ATOM, $decodedJson['dateCreated']);
        $this->assertEquals("2016-11-11 09:45:10", $creationDate->format('Y-m-d H:i:s'));
        $this->assertEquals("Finished Task", $decodedJson['task']);
        $this->assertEquals(["email"], $decodedJson['contexts']);
        $this->assertEquals(["exampleProject"], $decodedJson['projects']);
    }

    public function testFormatWithFlag() {
        $task = new Gravitask\Task\TaskItem();
        $task->setStatus(Gravitask\Task\TaskItem::STATUS_COMPLETED);
        $task->setCompletionDate(\DateTime::createFromFormat("Y-m-d H:i:s", "2016-11-12 09:00:04"));
        $task->setPriority("B");
        $task->setCreationDate(\DateTime::createFromFormat("Y-m-d H:i:s", "2016-11-11 08:30:12"));
        $task->setTask("Finished Task");
        $task->setContexts(["email"]);
        $task->setProjects(["exampleProject"]);

        $jsonOutput = $this->formatter->format($task, [0]);
        $decodedJson = json_decode($jsonOutput, true);

        $this->assertEquals(true, $decodedJson['completed']);

        $completionDate = \DateTime::createFromFormat(\DateTime::ATOM, $decodedJson['dateCompleted']);
        $this->assertEquals("2016-11-12 09:00:04", $completionDate->format("Y-m-d H:i:s"));
        $this->assertEquals("B", $decodedJson['priority']);

        $creationDate = \DateTime::createFromFormat(\DateTime::ATOM, $decodedJson['dateCreated']);
        $this->assertEquals("2016-11-11 08:30:12", $creationDate->format('Y-m-d H:i:s'));
        $this->assertEquals("Finished Task", $decodedJson['task']);
        $this->assertEquals(["email"], $decodedJson['contexts']);
        $this->assertEquals(["exampleProject"], $decodedJson['projects']);
    }
}

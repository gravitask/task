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
        $task->setCompletionDate("2016-11-12");
        $task->setPriority("B");
        $task->setCreationDate("2016-11-11");
        $task->setTask("Finished Task");
        $task->setContexts(["email"]);

        $jsonOutput = $this->formatter->format($task);

        $expected = '{"completed":true,"dateCompleted":"2016-11-12","priority":"B",' .
            '"dateCreated":"2016-11-11","task":"Finished Task","contexts":["email"]}';

        $this->assertEquals($expected, $jsonOutput);
    }
}

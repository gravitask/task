<?php

require __DIR__ .'/../vendor/autoload.php';

class TodoTxtFormatterTest extends PHPUnit_Framework_TestCase
{
    public function testBasicTaskFormat() {
        $formatter = new Gravitask\Formatter\TodoTxtFormatter();
        $task = new \Gravitask\TaskItem();
        $task->setTask("Basic Task!");

        $expectedResult = "Basic Task!";

        $result = $formatter->format($task);

        $this->assertEquals($expectedResult, $result);
    }

    public function testFormatComplex() {
        $formatter = new Gravitask\Formatter\TodoTxtFormatter();
        $task = new \Gravitask\TaskItem();
        $task->setTask("Basic Task");
        $task->setCreationDate("2016-01-02");
        $task->setContexts(["email", "computer"]);
        $task->setProjects(["secretProject"]);
        $task->setPriority("D");

        $expectedResult = "(D) 2016-01-02 Basic Task @email @computer +secretProject";

        $result = $formatter->format($task);

        $this->assertEquals($expectedResult, $result);
    }

    public function testFormatCompletedWithNoCompletionDate() {
        $formatter = new Gravitask\Formatter\TodoTxtFormatter();
        $task = new \Gravitask\TaskItem();
        $task->setStatus(Gravitask\TaskItem::STATUS_COMPLETED);
        $task->setTask("Some Task");
        $task->setCreationDate("2016-02-04");
        $task->setContexts(["email"]);
        $task->setPriority("A");

        $expectedResult = "(A) 2016-02-04 Some Task @email";

        $result = $formatter->format($task);

        $this->assertEquals($expectedResult, $result);
    }

    public function testFormatWithTwoDates() {
        $formatter = new Gravitask\Formatter\TodoTxtFormatter();
        $task = new \Gravitask\TaskItem();
        $task->setTask("Some Task");
        $task->setCreationDate("2016-02-04");
        $task->setCompletionDate("2016-02-04");
        $task->setContexts(["email"]);
        $task->setPriority("A");

        $expectedResult = "(A) 2016-02-04 Some Task @email";

        $result = $formatter->format($task);

        $this->assertEquals($expectedResult, $result);
    }

    public function testFormatForCompletedTask() {
        $formatter = new Gravitask\Formatter\TodoTxtFormatter();
        $task = new \Gravitask\TaskItem();
        $task->setStatus(Gravitask\TaskItem::STATUS_COMPLETED);
        $task->setTask("Finished Task");
        $task->setCreationDate("2016-11-11");
        $task->setCompletionDate("2016-11-12");
        $task->setContexts(["email"]);
        $task->setPriority("B");

        $expectedResult = "x 2016-11-12 (B) 2016-11-11 Finished Task @email";

        $result = $formatter->format($task);

        $this->assertEquals($expectedResult, $result);
    }

    public function testFormatPreventDuplicateContexts() {
        $formatter = new Gravitask\Formatter\TodoTxtFormatter();
        $task = new \Gravitask\TaskItem();
        $task->setTask("Simple @email Task");
        $task->setContexts(["email"]);

        $expectedResult = "Simple @email Task";

        $result = $formatter->format($task);

        $this->assertEquals($expectedResult, $result);
    }

    public function testFormatPreventDuplicateProjects() {
        $formatter = new Gravitask\Formatter\TodoTxtFormatter();
        $task = new \Gravitask\TaskItem();
        $task->setTask("Simple Task to finish +projectone soon");
        $task->setProjects(["projectone"]);

        $expectedResult = "Simple Task to finish +projectone soon";

        $result = $formatter->format($task);

        $this->assertEquals($expectedResult, $result);
    }
}
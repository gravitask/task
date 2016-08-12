<?php

require __DIR__ . '/../../vendor/autoload.php';

use \Gravitask\Task\Parser\TodoTxtParser;

class TodoTxtParserTest extends PHPUnit_Framework_TestCase
{
    /** @var Gravitask\Task\Parser\TodoTxtParser */
    private $parser;

    function setUp() {
        $this->parser = new TodoTxtParser();
    }

    function testBasicTask() {
        $input = "Dance";
        $result = $this->parser->parse($input);
        $this->assertEquals("Dance", $result->getTask());
    }

    function testPriority() {
        $input = "(C) Task with priority 3";
        $result = $this->parser->parse($input);
        $this->assertEquals("C", $result->getPriority());
    }

    function testPriorityMultiple() {
        $input = "(C) (B) (D) Task with priority 3";
        $result = $this->parser->parse($input);
        $this->assertEquals("C", $result->getPriority());
    }

    function testPriorityInvalid() {
        $input = "(C)Invalid task with priority 3";
        $result = $this->parser->parse($input);
        $this->assertEquals(null, $result->getPriority());
    }

    function testPriorityBadChars() {
        $input = "(d)Invalid task with priority 3";
        $result = $this->parser->parse($input);
        $this->assertEquals(null, $result->getPriority());
    }

    function testPriorityTooManyChars() {
        $input = "(BB) Task with priority BB";
        $result = $this->parser->parse($input);
        $this->assertEquals(null, $result->getPriority());
    }

    function testContexts() {
        $input = "Test task @email @home";
        $contexts = array("email", "home");

        $result = $this->parser->parse($input);

        $this->assertCount(2, $result->getContexts());
        $this->assertEquals($contexts, $result->getContexts());
    }

    function testContextsAtStart() {
        $input = "@GroceryStore Eskimo pies";
        $contexts = array("GroceryStore");

        $result = $this->parser->parse($input);

        $this->assertCount(1, $result->getContexts());
        $this->assertEquals($contexts, $result->getContexts());
    }

    function testProjects() {
        $input = "Test task +SecretProject +TestProject +AnotherProject";
        $projects = array("SecretProject", "TestProject", "AnotherProject");

        $result = $this->parser->parse($input);

        $this->assertCount(3, $result->getProjects());
        $this->assertEquals($projects, $result->getProjects());
    }

    function testCreationDate() {
        $input = "2016-01-01 Test task";

        $result = $this->parser->parse($input);

        $this->assertEquals("2016-01-01", $result->getCreationDate()->format('Y-m-d'));
    }

    function testStatus() {
        $input = "x 2016-01-02 Completed task";

        $result = $this->parser->parse($input);

        $this->assertEquals("2016-01-02", $result->getCompletionDate()->format("Y-m-d"));
        $this->assertEquals(\Gravitask\Task\TaskItem::STATUS_COMPLETED, $result->getStatus());
    }
    function testStatusWithoutCompletionDate() {
        $input = "x Non-completed task";

        $result = $this->parser->parse($input);

        $this->assertEquals(null, $result->getCompletionDate());
        $this->assertEquals(\Gravitask\Task\TaskItem::STATUS_ACTIVE, $result->getStatus());
    }

    function testStatusBasic() {
        $input = "x 2011-11-12 Dance";

        $result = $this->parser->parse($input);

        $this->assertEquals("2011-11-12", $result->getCompletionDate()->format("Y-m-d"));
        $this->assertEquals(\Gravitask\Task\TaskItem::STATUS_COMPLETED, $result->getStatus());
    }

    function testTaskDescription() {
        $input = "(A) 2017-05-05 Task name";
        $taskDesc = "Task name";

        $result = $this->parser->parse($input);

        $this->assertEquals($taskDesc, $result->getTask());
    }


    function testComplexParse() {
        $input = "(B) 2016-02-05 Test task @email @home +secretProject";
        $contexts = array("email", "home");
        $projects = array("secretProject");
        $creationDate = "2016-02-05";
        $taskDesc = "Test task @email @home +secretProject";

        $result = $this->parser->parse($input);

        $this->assertCount(2, $result->getContexts());
        $this->assertEquals($contexts, $result->getContexts());
        $this->assertEquals($projects, $result->getProjects());
        $this->assertEquals("B", $result->getPriority());
        $this->assertEquals($creationDate, $result->getCreationDate()->format('Y-m-d'));
        $this->assertEquals(null, $result->getCompletionDate());
        $this->assertEquals($taskDesc, $result->getTask());
    }

    function testComplexParseTwo() {
        $input = "x 2016-02-10 2016-02-05 Test task @email @home +secretProject";
        $taskDesc = "Test task @email @home +secretProject";
        $contexts = array("email", "home");
        $projects = array("secretProject");
        $creationDate = "2016-02-05";
        $completionDate = "2016-02-10";
        $status = \Gravitask\Task\TaskItem::STATUS_COMPLETED;

        $result = $this->parser->parse($input);

        $this->assertCount(2, $result->getContexts());
        $this->assertEquals($contexts, $result->getContexts());
        $this->assertEquals($projects, $result->getProjects());
        $this->assertEquals($creationDate, $result->getCreationDate()->format("Y-m-d"));
        $this->assertEquals($completionDate, $result->getCompletionDate()->format("Y-m-d"));
        $this->assertEquals($status, $result->getStatus());
        $this->assertEquals($taskDesc, $result->getTask());
    }

    function testComplexParseThree() {
        $input = "x 2016-02-05 Test task @email @home +secretProject";
        $taskDesc = "Test task @email @home +secretProject";
        $contexts = array("email", "home");
        $projects = array("secretProject");
        $completionDate = "2016-02-05";
        $status = \Gravitask\Task\TaskItem::STATUS_COMPLETED;

        $result = $this->parser->parse($input);

        $this->assertCount(2, $result->getContexts());
        $this->assertEquals($contexts, $result->getContexts());
        $this->assertEquals($projects, $result->getProjects());
        $this->assertEquals($completionDate, $result->getCompletionDate()->format("Y-m-d"));
        $this->assertEquals($status, $result->getStatus());
        $this->assertEquals($taskDesc, $result->getTask());
    }

    function testComplexParseFour() {
        $input = "x Test task @email @home +secretProject";
        $taskDesc = "x Test task @email @home +secretProject";
        $contexts = array("email", "home");
        $projects = array("secretProject");
        $status = \Gravitask\Task\TaskItem::STATUS_ACTIVE;

        $result = $this->parser->parse($input);

        $this->assertCount(2, $result->getContexts());
        $this->assertEquals($contexts, $result->getContexts());
        $this->assertEquals($projects, $result->getProjects());
        $this->assertEquals(null, $result->getCreationDate());
        $this->assertEquals(null, $result->getCompletionDate());
        $this->assertEquals($status, $result->getStatus());
        $this->assertEquals($taskDesc, $result->getTask());
    }

    function testParseKeyValue() {
        $input = "Basic Task pri:B due:2016-01-14";
        $priority = "B";
        $extrasDueDate = "2016-01-14";

        $result = $this->parser->parse($input);

        $this->assertCount(1, $result->getMetadata());
        $this->assertEquals($priority, $result->getPriority());
        $this->assertEquals($extrasDueDate, $result->getMetadata()['DUE']);
    }

    function testParseKeyValueOverrulePriority() {
        $input = "(A) Two priorities pri:B due:2016-01-14";
        $priority = "B";
        $extrasDueDate = "2016-01-14";

        $result = $this->parser->parse($input);

        $this->assertCount(1, $result->getMetadata());
        $this->assertEquals($priority, $result->getPriority());
        $this->assertEquals($extrasDueDate, $result->getMetadata()['DUE']);
    }

    function testParseMetadataWithIgnoreFlag() {
        $input = "(A) Two priorities pri:B due:2016-01-14";
        $priority = "A";

        $this->parser->setFlags([TodoTxtParser::FLAG_IGNORE_METADATA]);
        $result = $this->parser->parse($input);

        $this->assertCount(0, $result->getMetadata());
        $this->assertEquals($priority, $result->getPriority());
    }

    function testParseMetadataWithIgnoreFlagArgument() {
        $input = "(A) Two priorities Part 2 pri:B due:2016-01-14";
        $priority = "A";

        $result = $this->parser->parse($input, [TodoTxtParser::FLAG_IGNORE_METADATA]);

        $this->assertCount(0, $result->getMetadata());
        $this->assertEquals($priority, $result->getPriority());
    }
}

<?php

require __DIR__ . '/../../vendor/autoload.php';

class JsonParserTest extends PHPUnit_Framework_TestCase
{
    /** @var Gravitask\Task\Parser\JsonParser */
    private $parser;

    function setUp() {
        $this->parser = new \Gravitask\Task\Parser\JsonParser();
    }

    function testBasicTask() {
        $input = '{
        "task": "Dance"
        }';

        $result = $this->parser->parse($input);
        $this->assertEquals("Dance", $result->getTask());
    }

    function testBasicTaskWithFlag() {
        $input = '{
        "task": "Paint"
        }';

        $result = $this->parser->parse($input, [0]);
        $this->assertEquals("Paint", $result->getTask());
    }

    function testTaskAllKeys() {
        $input = '{
        "completed": true,
        "dateCompleted": "2010-10-18",
        "priority": "D",
        "dateCreated": "2010-10-12",
        "task": "Dance",
        "contexts": ["phone"],
        "projects": ["danceContest"]
        }';

        $result = $this->parser->parse($input);
        $this->assertEquals(\Gravitask\Task\TaskItem::STATUS_COMPLETED, $result->getStatus());
        $this->assertEquals("2010-10-12", $result->getCreationDate());
        $this->assertEquals("D", $result->getPriority());
        $this->assertEquals("2010-10-18", $result->getCompletionDate());
        $this->assertEquals("Dance", $result->getTask());
        $this->assertEquals(["phone"], $result->getContexts());
        $this->assertEquals(["danceContest"], $result->getProjects());
    }

    function testTaskInvalidData() {
        $input = '{
        "completed": "astring?",
        "dateCompleted": "not-a-date",
        "priority": "12",
        "dateCreated": "bad-date-format",
        "task": "Dance",
        "contexts": ["phone"],
        "projects": ["danceContest"]
        }';

        $result = $this->parser->parse($input);
        $this->assertEquals(\Gravitask\Task\TaskItem::STATUS_ACTIVE, $result->getStatus());
        $this->assertEquals(null, $result->getCreationDate());
        $this->assertEquals(null, $result->getPriority());
        $this->assertEquals(null, $result->getCompletionDate());
        $this->assertEquals("Dance", $result->getTask());
        $this->assertEquals(["phone"], $result->getContexts());
        $this->assertEquals(["danceContest"], $result->getProjects());
    }

    function testTaskWithObjectValue() {
        $input = '{
        "task": { "key": "value" }
        }';

        $result = $this->parser->parse($input);
        $this->assertEquals(null, $result->getTask());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    function testTaskWithInvalidJson() {
        $input = 'this is not JSON!';

        $result = $this->parser->parse($input);

        $this->assertFalse(null, $result->getTask());
    }
}

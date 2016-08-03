<?php

require __DIR__ . '/../../vendor/autoload.php';

use Gravitask\Task\Parser\BaseParser;

class BaseParserTest extends PHPUnit_Framework_TestCase
{
    /** @var BaseParser */
    private $parser;

    public function setUp() {
        $this->parser = new BaseParser();
    }

    public function testSetFlags() {
        $flags = [
            1, 2, 16
        ];

        $this->parser->setFlags($flags);

        $this->assertEquals($flags, $this->parser->getFlags());
    }

    public function testSetFlagsWithEmptyArray() {
        $flags = [
            1, 2, 16
        ];

        $this->parser->setFlags($flags);
        $this->assertEquals($flags, $this->parser->getFlags());

        $this->parser->setFlags([]);
        $this->assertCount(0, $this->parser->getFlags());
        $this->assertEquals([], $this->parser->getFlags());
    }

    public function testAddFlags() {
        $flags = [
            1, 2, 16
        ];

        $this->parser->setFlags($flags);
        $this->parser->addFlag(8);

        $expectedFlags = [1, 2, 16, 8];

        $this->assertEquals($expectedFlags, $this->parser->getFlags());
    }

    public function testRemoveFlag() {
        $flags = [
            1, 2, 4, 8, 16
        ];

        $this->parser->setFlags($flags);
        $expectedFlags = [1, 2, 4, 8, 16];
        $this->assertEquals($expectedFlags, $this->parser->getFlags());

        $this->parser->removeFlag(4);
        $expectedFlags = [1, 2, 8, 16];
        $this->assertEquals($expectedFlags, $this->parser->getFlags());

        $this->parser->removeFlag(1);
        $expectedFlags = [2, 8, 16];
        $this->assertEquals($expectedFlags, $this->parser->getFlags());
    }

    public function testHasFlag() {
        $flags = [
            1, 2, 4, 8
        ];

        $this->parser->setFlags($flags);

        $this->assertTrue($this->parser->hasFlag(1));
        $this->assertTrue($this->parser->hasFlag(2));
        $this->assertTrue($this->parser->hasFlag(4));
        $this->assertTrue($this->parser->hasFlag(8));
    }

    public function testHasFlagMissing() {
        $flags = [
            1, 2, 4, 8
        ];

        $this->parser->setFlags($flags);

        $this->assertFalse($this->parser->hasFlag(16));
    }
}

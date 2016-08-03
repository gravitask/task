<?php

require __DIR__ . '/../../vendor/autoload.php';

use Gravitask\Task\Formatter\BaseFormatter;

class BaseFormatterTest extends PHPUnit_Framework_TestCase
{
    /** @var BaseFormatter */
    private $formatter;

    public function setUp() {
        $this->formatter = new BaseFormatter();
    }

    public function testSetFlags() {
        $flags = [
            1, 2
        ];

        $this->formatter->setFlags($flags);
        $this->assertEquals($flags, $this->formatter->getFlags());
    }

    public function testSetFlagsWithEmptyArray() {
        $flags = [
            1, 2
        ];

        $this->formatter->setFlags($flags);
        $this->assertEquals($flags, $this->formatter->getFlags());

        $this->formatter->setFlags([]);
        $this->assertCount(0, $this->formatter->getFlags());
    }

    public function testAddFlags() {
        $flags = [
            1, 2
        ];

        $this->formatter->setFlags($flags);
        $this->formatter->addFlag(32);

        $expectedFlags = [1, 2, 32];
        $this->assertEquals($expectedFlags, $this->formatter->getFlags());
    }

    public function testRemoveFlag() {
        $flags = [
            1, 32, 64
        ];

        $this->formatter->setFlags($flags);
        $this->assertEquals($flags, $this->formatter->getFlags());

        $this->formatter->removeFlag(32);
        $expectedFlags = [1, 64];
        $this->assertEquals($expectedFlags, $this->formatter->getFlags());
    }

    public function testHasFlag() {
        $flags = [
            1, 2, 4
        ];

        $this->formatter->setFlags($flags);
        $this->assertTrue($this->formatter->hasFlag(1));
        $this->assertTrue($this->formatter->hasFlag(2));
        $this->assertTrue($this->formatter->hasFlag(4));
        $this->assertFalse($this->formatter->hasFlag(32));
    }
}

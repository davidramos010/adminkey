<?php

/**
 * @group ready
 */
class PieTest extends \Codeception\Test\Unit
{
    use factorenergia\JpGraph\UnitTest\UnitTestTrait;

    public static $fixTures    = [];
    public static $files       = null;
    public static $exampleRoot = null;
    public static $ranTests    = [];

    protected function _before() {}

    protected function _after() {}

    public function test3dPiePlotExample()
    {
        $this->traverseFixtureGroup($this->fixTures(__METHOD__));
    }

    public function testPiePlotExample()
    {
        $this->traverseFixtureGroup($this->fixTures(__METHOD__));
    }

    public function testExample5OfPiePlot()
    {
        $this->traverseFixtureGroup($this->fixTures(__METHOD__));
    }

    public function testPiePlotWithAbsoluteLabels()
    {
        $this->traverseFixtureGroup($this->fixTures(__METHOD__));
    }

    public function testLabelGuideLines()
    {
        $this->traverseFixtureGroup($this->fixTures(__METHOD__));
    }

    public function testStringLabelsWithValues()
    {
        $this->traverseFixtureGroup($this->fixTures(__METHOD__));
    }

    public function testpie3dFileIterator()
    {
        $this->traverseFixtureGroup($this->fixTures(__METHOD__));
    }

    public function testExampleOfPiePlotWithAbsoluteLabels()
    {
        $this->traverseFixtureGroup($this->fixTures(__METHOD__));
    }

    public function testPiefileiterator()
    {
        $this->traverseFixtureGroup($this->fixTures(__METHOD__));
    }

    public function testpielabelsFileIterator()
    {
        $this->traverseFixtureGroup($this->fixTures(__METHOD__));
    }

    public function test200()
    {
        $this->traverseFixtureGroup($this->fixTures(__METHOD__));
    }

    public function testExample4OfPiePlot()
    {
        $this->traverseFixtureGroup($this->fixTures(__METHOD__));
    }

    public function testWithHidden0Labels()
    {
        $this->traverseFixtureGroup($this->fixTures(__METHOD__));
    }

    public function testFileIterator()
    {
        self::$genericFixtures = array_reduce(self::$files, function ($carry, $file) {
            $carry = $this->_fileCheck($file, $carry, true);
            return $carry;
        }, self::$genericFixtures);
    }
}
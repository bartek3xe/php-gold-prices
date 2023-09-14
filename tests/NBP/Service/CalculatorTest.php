<?php

namespace App\Tests\NBP\Service;

use App\NBP\Service\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    private Calculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new Calculator();;

        parent::setUp();
    }

    public function testCalcAverageFromArrayWithValidValues()
    {
        $array  = [1, 2, 3, 4, 5];
        $result = $this->calculator->calcAverageFromArray($array);

        $this->assertEquals(3.0, $result);
    }

    public function testCalcAverageFromArrayWithEmptyArray()
    {
        $array  = [];
        $result = $this->calculator->calcAverageFromArray($array);

        $this->assertEquals(0, $result);
    }

    public function testHasNumericValuesWithNumericArray()
    {
        $reflectionMethod = new \ReflectionMethod($this->calculator, 'hasNumericValues');

        $array  = [1, 2, 3, 4, 5];
        $result = $reflectionMethod->invoke($this->calculator, $array);

        $this->assertTrue($result);
    }

    public function testHasNumericValuesWithNonNumericArray()
    {
        $reflectionMethod = new \ReflectionMethod($this->calculator, 'hasNumericValues');

        $array  = ['a', 'b', 'c'];
        $result = $reflectionMethod->invoke($this->calculator, $array);

        $this->assertFalse($result);
    }
}

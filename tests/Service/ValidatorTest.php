<?php

namespace App\Tests\Service;

use App\Service\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    public function testValidGoldDateFormat(): void
    {
        $this->assertTrue(Validator::isValidGoldDateFormat('2021-01-01T00:00:00Z'));
        $this->assertFalse(Validator::isValidGoldDateFormat('2021-01-01'));
        $this->assertFalse(Validator::isValidGoldDateFormat('2021-01-01T00:00:00.123Z'));
    }

    public function testValidGoldDateRangeDuration(): void
    {
        $validator = new Validator();

        $fromDate = new \DateTime('2021-01-01T00:00:00Z');
        $toDate   = new \DateTime('2021-01-21T00:00:00Z');
        $this->assertTrue($validator->isValidGoldDateRangeDuration($fromDate, $toDate));

        $fromDate = new \DateTime('2021-01-01T00:00:00Z');
        $toDate   = new \DateTime('2021-05-01T00:00:00Z');
        $this->assertFalse($validator->isValidGoldDateRangeDuration($fromDate, $toDate));
    }
}

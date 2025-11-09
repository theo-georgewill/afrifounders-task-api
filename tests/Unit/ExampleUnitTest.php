<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ExampleUnitTest extends TestCase
{
    #[Test]
    public function it_can_run_a_basic_test()
    {
        $value = true;
        $this->assertTrue($value);
    }
}

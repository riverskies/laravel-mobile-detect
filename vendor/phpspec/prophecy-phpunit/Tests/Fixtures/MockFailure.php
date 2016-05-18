<?php

namespace Prophecy\PhpUnit\Tests\Fixtures;

use Prophecy\PhpUnit\ProphecyTestCase;

class MockFailure extends ProphecyTestCase
{
    public function testMethod()
    {
        $prophecy = $this->prophesize('DateTime');

        $prophecy->format('Y-m-d')->shouldBeCalledTimes(2);

        $double = $prophecy->reveal();

        $double->format('Y-m-d');
    }
}

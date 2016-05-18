<?php

namespace Prophecy\PhpUnit\Tests\Fixtures;

use Prophecy\PhpUnit\ProphecyTestCase;

class SpyFailure extends ProphecyTestCase
{
    public function testMethod()
    {
        $prophecy = $this->prophesize('DateTime');

        $prophecy->reveal();

        $prophecy->format('Y-m-d')->shouldHaveBeenCalled();
    }
}

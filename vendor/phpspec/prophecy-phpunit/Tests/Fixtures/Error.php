<?php

namespace Prophecy\PhpUnit\Tests\Fixtures;

use Prophecy\PhpUnit\ProphecyTestCase;

class Error extends ProphecyTestCase
{
    public function testMethod()
    {
        $prophecy = $this->prophesize('stdClass');

        $prophecy->talk()->willReturn('Hello world!');
    }
}

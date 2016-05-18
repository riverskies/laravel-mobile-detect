<?php

namespace Prophecy\PhpUnit\Tests;

use Prophecy\PhpUnit\Tests\Fixtures\Error;
use Prophecy\PhpUnit\Tests\Fixtures\MockFailure;
use Prophecy\PhpUnit\Tests\Fixtures\SpyFailure;
use Prophecy\PhpUnit\Tests\Fixtures\Success;

class ProphecyTestCaseTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        // Define the constant because our tests are running PHPUnit testcases themselves
        if (!defined('PHPUNIT_TESTSUITE')) {
            define('PHPUNIT_TESTSUITE', true);
        }
    }

    public function testSuccess()
    {
        $test = new Success('testMethod');
        $result = $test->run();

        $this->assertEquals(0, $result->errorCount());
        $this->assertEquals(0, $result->failureCount());
        $this->assertCount(1, $result);
        $this->assertEquals(1, $test->getNumAssertions());
    }

    public function testSpyPredictionFailure()
    {
        $test = new SpyFailure('testMethod');
        $result = $test->run();

        $this->assertEquals(0, $result->errorCount());
        $this->assertEquals(1, $result->failureCount());
        $this->assertCount(1, $result);
        $this->assertEquals(1, $test->getNumAssertions());
    }

    public function testMockPredictionFailure()
    {
        $test = new MockFailure('testMethod');
        $result = $test->run();

        $this->assertEquals(0, $result->errorCount());
        $this->assertEquals(1, $result->failureCount());
        $this->assertCount(1, $result);
        $this->assertEquals(1, $test->getNumAssertions());
    }

    public function testDoublingError()
    {
        $test = new Error('testMethod');
        $result = $test->run();

        $this->assertEquals(1, $result->errorCount());
        $this->assertEquals(0, $result->failureCount());
        $this->assertCount(1, $result);
        $this->assertEquals(0, $test->getNumAssertions());
    }
}

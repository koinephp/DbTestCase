<?php

namespace KoineTest\PHPUnit;

use Koine\PHPUnit\DbTestCase;
use PHPUnit_Framework_TestCase;

/**
 * @author Marcelo Jacobus <marcelo.jacobus@gmail.com>
 */
class DbTestCaseTest extends DbTestCase
{
    public function setUp()
    {
    }

    /**
     * @test
     */
    public function extendsPhpUnitTestCase()
    {
        $this->assertInstanceOf('PHPUnit_Framework_TestCase', $this);
    }
}

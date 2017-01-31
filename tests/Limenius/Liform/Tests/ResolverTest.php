<?php

namespace Limenius\Liform\Tests;

use Limenius\Liform\Resolver;

/**
 * Class: ResolverTest
 *
 * @see \PHPUnit_Framework_TestCase
 */
class ResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testConstruct
     *
     */
    public function testConstruct()
    {
        $resolver = new Resolver();
        $this->assertInstanceOf(Resolver::class, $resolver);
    }
}

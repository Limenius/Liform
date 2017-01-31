<?php

namespace Limenius\Liform\Tests;

use Limenius\Liform\Resolver;

class ResolverTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $resolver = new Resolver();
        $this->assertInstanceOf(Resolver::class, $resolver);
    }

}

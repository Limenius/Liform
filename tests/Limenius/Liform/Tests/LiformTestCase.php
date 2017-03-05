<?php

namespace Limenius\Liform\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Forms;
use Limenius\Liform\Form\Extension\AddLiformExtension;

/**
 *
 * Common test utils
 *
 * @see TestCase
 */
class LiformTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FormFactoryInterface
     */
    protected $factory;

    protected function setUp()
    {
        $ext = new AddLiformExtension();
        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions([])
            ->addTypeExtensions([$ext])
            ->getFormFactory();
    }
}

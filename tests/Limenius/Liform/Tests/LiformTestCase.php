<?php

namespace Limenius\Liform\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Forms;
use Limenius\Liform\Form\Extension\AddLiformExtension;
use Symfony\Component\Translation\Translator;

/**
 *
 * Common test utils
 *
 * @see TestCase
 */
class LiformTestCase extends TestCase
{
    /**
     * @var FormFactoryInterface
     */
    protected $factory;

    /**
     * @var Translator
     */
    protected $translator;

    protected function setUp()
    {
        $ext = new AddLiformExtension();
        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions([])
            ->addTypeExtensions([$ext])
            ->getFormFactory();

        $this->translator = $this->createMock(Translator::class);
    }
}

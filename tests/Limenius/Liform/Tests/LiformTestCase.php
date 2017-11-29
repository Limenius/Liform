<?php

/*
 * This file is part of the Limenius\Liform package.
 *
 * (c) Limenius <https://github.com/Limenius/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Limenius\Liform\Tests;

use Limenius\Liform\Form\Extension\AddLiformExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Forms;
use Symfony\Component\Translation\TranslatorInterface;

/**
 *
 * @author Nacho Martín <nacho@limenius.com>
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
     * @var TranslatorInterface
     */
    protected $translator;

    protected function setUp()
    {
        $ext = new AddLiformExtension();
        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions([])
            ->addTypeExtensions([$ext])
            ->getFormFactory();

        $this->translator = $this->createMock(TranslatorInterface::class);
    }
}

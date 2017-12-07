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

use Limenius\Liform\Resolver;
use Limenius\Liform\Exception\TransformerException;
use Limenius\Liform\Transformer\StringTransformer;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * @author Nacho Martín <nacho@limenius.com>
 *
 * @see TypeTestCase
 */
class ResolverTest extends TypeTestCase
{
    public function testConstruct()
    {
        $resolver = new Resolver();
        $this->assertInstanceOf(Resolver::class, $resolver);
    }

    /**
     * @expectedException \Limenius\Liform\Exception\TransformerException
     */
    public function testCannotResolve()
    {
        $resolver = new Resolver();
        $form = $this->factory->create(TextType::class);
        $this->assertArrayHasKey('transformer', $resolver->resolve($form));
    }

    public function testResolve()
    {
        $resolver = new Resolver();
        $stub = $this->createMock(StringTransformer::class);
        $resolver->setTransformer('text', $stub);
        $form = $this->factory->create(TextType::class);
        $this->assertArrayHasKey('transformer', $resolver->resolve($form));
    }
}

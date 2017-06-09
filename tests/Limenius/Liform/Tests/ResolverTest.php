<?php

namespace Limenius\Liform\Tests;

use Limenius\Liform\Resolver;
use Limenius\Liform\Exception\TransformerException;
use Limenius\Liform\Transformer\StringTransformer;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * Class: ResolverTest
 *
 * @see TypeTestCase
 */
class ResolverTest extends TypeTestCase
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

    /**
     * testCannotResolve
     *
     * @expectedException \Limenius\Liform\Exception\TransformerException
     */
    public function testCannotResolve()
    {
        $resolver = new Resolver();
        $form = $this->factory->create(TextType::class);
        $this->assertArrayHasKey('transformer', $resolver->resolve($form));
    }

    /**
     * testResolve
     */
    public function testResolve()
    {
        $resolver = new Resolver();
        $stub = $this->createMock(StringTransformer::class);
        $resolver->setTransformer('text', $stub);
        $form = $this->factory->create(TextType::class);
        $this->assertArrayHasKey('transformer', $resolver->resolve($form));
    }
}

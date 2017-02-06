<?php

namespace Limenius\Liform\Tests;

use Limenius\Liform\Resolver;
use Limenius\Liform\Exception\TransformerException;
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
     */
    public function testCannotResolve()
    {
        $this->expectException(TransformerException::class);
        $resolver = new Resolver();
        $form = $this->factory->create(TextType::class);
        $this->assertArrayHasKey('transformer', $resolver->resolve($form));
    }

    /**
     * testResolve
     *
     */
    public function testResolve()
    {
        $resolver = new Resolver();
        $resolver->setDefaultTransformers();
        $form = $this->factory->create(TextType::class);
        $this->assertArrayHasKey('transformer', $resolver->resolve($form));
    }
}

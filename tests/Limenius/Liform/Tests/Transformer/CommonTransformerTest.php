<?php

/*
 * This file is part of the Limenius\Liform package.
 *
 * (c) Limenius <https://github.com/Limenius/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Limenius\Liform\Tests\Liform\Transformer;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Limenius\Liform\Transformer\CompoundTransformer;
use Limenius\Liform\Transformer\StringTransformer;
use Limenius\Liform\Resolver;
use Limenius\Liform\Tests\LiformTestCase;

/**
 * @author Nacho Martín <nacho@limenius.com>
 *
 * @see TypeTestCase
 */
class CommonTransformerTest extends LiformTestCase
{
    public function testRequired()
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                TextType::class,
                ['required' => true]
            );
        $resolver = new Resolver();
        $resolver->setTransformer('text', new StringTransformer($this->translator));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);

        $this->assertTrue(is_array($transformed));
        $this->assertArrayHasKey('required', $transformed);
        $this->assertTrue(is_array($transformed['required']));
        $this->assertContains('firstName', $transformed['required']);
    }

    public function testDescription()
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                TextType::class,
                ['liform' => ['description' => 'A word that references you in the hash of the world']]
            );
        $resolver = new Resolver();
        $resolver->setTransformer('text', new StringTransformer($this->translator));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);

        $this->assertTrue(is_array($transformed));
        $this->assertArrayHasKey('description', $transformed['properties']['firstName']);
    }

    public function testLabel()
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                TextType::class,
                ['label' => 'a label']
            );
        $resolver = new Resolver();
        $resolver->setTransformer('text', new StringTransformer($this->translator));
        $this->translator
            ->expects($this->exactly(2))
            ->method('trans')
            ->willReturn('a label');
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);

        $this->assertTrue(is_array($transformed));
        $this->assertArrayHasKey('title', $transformed['properties']['firstName']);
        $this->assertEquals('a label', $transformed['properties']['firstName']['title']);
    }

    public function testWidget()
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                TextType::class,
                ['liform' => ['widget' => 'my widget']]
            );
        $resolver = new Resolver();
        $resolver->setTransformer('text', new StringTransformer($this->translator));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);

        $this->assertTrue(is_array($transformed));
        $this->assertArrayHasKey('widget', $transformed['properties']['firstName']);
    }

    public function testWidgetViaTransformerDefinition()
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                TextType::class
            );
        $resolver = new Resolver();
        $resolver->setTransformer('text', new StringTransformer($this->translator), 'widg');
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);

        $this->assertTrue(is_array($transformed));
        $this->assertArrayHasKey('widget', $transformed['properties']['firstName']);
    }
}

<?php

/*
 * This file is part of the Limenius\Liform package.
 *
 * (c) Limenius <https://github.com/Limenius/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Limenius\Liform\Tests\Transformer;

use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Limenius\Liform\Transformer\CompoundTransformer;
use Limenius\Liform\Transformer;
use Limenius\Liform\Resolver;
use Limenius\Liform\Tests\LiformTestCase;

/**
 * @author Nacho Martín <nacho@limenius.com>
 *
 * @see TypeTestCase
 */
class ChoiceTransformerTest extends LiformTestCase
{
    public function testChoice()
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                Type\ChoiceType::class,
                [
                    'choices' => ['a' => 'A', 'b' => 'B'],
                ]
            );

        // 4 times: firstName, form, and the two choices
        $this->translator->expects($this->exactly(4))
            ->method('trans')
            ->will($this->returnCallback(function ($str) {
                return $str.'-translated';
            }));

        $resolver = new Resolver();
        $resolver->setTransformer('choice', new Transformer\ChoiceTransformer($this->translator, null));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);
        $this->assertTrue(is_array($transformed));
        $this->assertArrayHasKey('enum_titles', $transformed['properties']['firstName']);
        $this->assertEquals(['a-translated', 'b-translated'], $transformed['properties']['firstName']['enum_titles']);
        $this->assertArrayHasKey('enum', $transformed['properties']['firstName']);
        $this->assertEquals(['A', 'B'], $transformed['properties']['firstName']['enum']);
    }

    public function testChoiceExpanded()
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                Type\ChoiceType::class,
                [
                    'choices' => ['a' => 'A', 'b' => 'B'],
                    'expanded' => true,
                ]
            );

        // 4 times: firstName, form, and the two choices
        $this->translator->expects($this->exactly(4))
            ->method('trans')
            ->will($this->returnCallback(function ($str) {
                return $str.'-translated';
            }));

        $resolver = new Resolver();
        $resolver->setTransformer('choice', new Transformer\ChoiceTransformer($this->translator, null));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);
        $this->assertTrue(is_array($transformed));
        $this->assertArrayHasKey('enum_titles', $transformed['properties']['firstName']);
        $this->assertEquals(['a-translated', 'b-translated'], $transformed['properties']['firstName']['enum_titles']);
        $this->assertArrayHasKey('enum', $transformed['properties']['firstName']);
        $this->assertEquals(['A', 'B'], $transformed['properties']['firstName']['enum']);
        $this->assertArrayHasKey('widget', $transformed['properties']['firstName']);
        $this->assertEquals('choice-expanded',  $transformed['properties']['firstName']['widget']);
    }

    public function testChoiceMultiple()
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                Type\ChoiceType::class,
                [
                    'choices' => ['a' => 'A', 'b' => 'B'],
                    'multiple' => true,
                ]
            );

        $resolver = new Resolver();
        $resolver->setTransformer('choice', new Transformer\ChoiceTransformer($this->translator, null));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);
        $this->assertTrue(is_array($transformed));
        $this->assertArrayHasKey('items', $transformed['properties']['firstName']);
        $this->assertEquals('array', $transformed['properties']['firstName']['type']);
        $this->assertArrayNotHasKey('widget', $transformed['properties']['firstName']);
    }

    public function testChoiceMultipleExpanded()
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                Type\ChoiceType::class,
                [
                    'choices' => ['a' => 'A', 'b' => 'B'],
                    'expanded' => true,
                    'multiple' => true,
                ]
            );

        $resolver = new Resolver();
        $resolver->setTransformer('choice', new Transformer\ChoiceTransformer($this->translator, null));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);
        $this->assertTrue(is_array($transformed));
        $this->assertArrayHasKey('items', $transformed['properties']['firstName']);
        $this->assertEquals('array', $transformed['properties']['firstName']['type']);
        $this->assertArrayHasKey('widget', $transformed['properties']['firstName']);
        $this->assertEquals('choice-multiple-expanded',  $transformed['properties']['firstName']['widget']);
    }
}

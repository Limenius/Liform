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

use Limenius\Liform\Resolver;
use Limenius\Liform\Tests\LiformTestCase;
use Limenius\Liform\Transformer\CompoundTransformer;
use Limenius\Liform\Transformer\StringTransformer;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Tests\AbstractFormTest;

/**
 * @author Nacho Martín <nacho@limenius.com>
 *
 * @see TypeTestCase
 */
class CompoundTransformerTest extends LiformTestCase
{
    public function testOrder()
    {
        $form = $this->factory->create(FormType::class)
            ->add('firstName', TextType::class)
            ->add('secondName', TextType::class);
        $resolver = new Resolver();
        $resolver->setTransformer('text', new StringTransformer($this->translator));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);

        $this->assertTrue(is_array($transformed));
        $this->assertEquals(1, $transformed['properties']['firstName']['propertyOrder']);
        $this->assertEquals(2, $transformed['properties']['secondName']['propertyOrder']);
    }

    public function testPriority()
    {
        $form = $this->factory->create(FormType::class)
            ->add('firstName', TextType::class, ['priority' => 1])
            ->add('secondName', TextType::class, ['priority' => 0]);
        $resolver = new Resolver();
        $resolver->setTransformer('text', new StringTransformer($this->translator));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);

        $this->assertTrue(is_array($transformed));
        $this->assertEquals(2, $transformed['properties']['firstName']['propertyOrder']);
        $this->assertEquals(1, $transformed['properties']['secondName']['propertyOrder']);
    }
}

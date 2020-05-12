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
class StringTransformerTest extends LiformTestCase
{
    public function testPattern()
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                TextType::class,
                ['attr' => ['pattern' => '.{5,}' ]]
            );
        $resolver = new Resolver();
        $resolver->setTransformer('text', new StringTransformer($this->translator));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);
        $this->assertTrue(is_array($transformed));
        $this->assertEquals('.{5,}', $transformed['properties']['firstName']['pattern']);
    }
}

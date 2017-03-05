<?php

namespace Limenius\Liform\Tests\Liform\Transformer;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Limenius\Liform\Transformer\CompoundTransformer;
use Limenius\Liform\Transformer\NumberTransformer;
use Limenius\Liform\Resolver;
use Limenius\Liform\Tests\LiformTestCase;

/**
 * Class: NumberTransformerTest
 *
 * @see TypeTestCase
 */
class NumberTransformerTest extends LiformTestCase
{

    /**
     * testPattern
     *
     */
    public function testPattern()
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'somefield',
                NumberType::class,
                ['liform' => ['widget' => 'widget']]
            );
        $resolver = new Resolver();
        $resolver->setTransformer('number', new NumberTransformer());
        $transformer = new CompoundTransformer($resolver);
        $transformed = $transformer->transform($form);
        $this->assertTrue(is_array($transformed));
        $this->assertEquals('number', $transformed['properties']['somefield']['type']);
        $this->assertEquals('widget', $transformed['properties']['somefield']['widget']);
    }
}

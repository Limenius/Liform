<?php

namespace Limenius\Liform\Tests\Liform\Transformer;

use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Limenius\Liform\Transformer\CompoundTransformer;
use Limenius\Liform\Transformer;
use Limenius\Liform\Resolver;
use Limenius\Liform\Tests\LiformTestCase;

/**
 * Class: ChoiceTransformerTest
 *
 * @see TypeTestCase
 */
class ChoiceTransformerTest extends LiformTestCase
{

    /**
     * testPattern
     *
     */
    public function testPattern()
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                Type\ChoiceType::class,
                [
                    'choices' => ['a' => 'A', 'b' => 'B'],
                ]
            );
        $resolver = new Resolver();
        $resolver->setTransformer('choice', new Transformer\ChoiceTransformer($this->translator, null));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);
        $this->assertTrue(is_array($transformed));
        $this->assertArrayHasKey('enum_titles', $transformed['properties']['firstName']);
        $this->assertEquals(['a', 'b'], $transformed['properties']['firstName']['enum_titles']);
        $this->assertArrayHasKey('enum', $transformed['properties']['firstName']);
        $this->assertEquals(['A', 'B'], $transformed['properties']['firstName']['enum']);
    }
}

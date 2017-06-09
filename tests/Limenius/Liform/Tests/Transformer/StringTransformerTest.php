<?php

namespace Limenius\Liform\Tests\Liform\Transformer;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Limenius\Liform\Transformer\CompoundTransformer;
use Limenius\Liform\Transformer\StringTransformer;
use Limenius\Liform\Resolver;
use Limenius\Liform\Tests\LiformTestCase;

/**
 * Class: StringTransformerTest
 *
 * @see TypeTestCase
 */
class StringTransformerTest extends LiformTestCase
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

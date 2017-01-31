<?php

namespace Limenius\Liform\Tests\Liform\Transformer;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Tests\AbstractFormTest;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Test\TypeTestCase;

use Limenius\Liform\Transformer\CompoundTransformer;
use Limenius\Liform\Transformer\StringTransformer;
use Limenius\Liform\Resolver;

class CompoundTransformerTest extends TypeTestCase
{

    public function testOrder()
    {
        $form = $this->factory->create(FormType::class)
            ->add('firstName', TextType::class)
            ->add('secondName', TextType::class);
        $resolver = new Resolver();
        $resolver->setTransformer('text', new StringTransformer());
        $transformer = new CompoundTransformer($resolver);
        $transformed = $transformer->transform($form);
        $this->assertTrue(is_array($transformed));
        $this->assertEquals(1, $transformed['properties']['firstName']['propertyOrder']);
        $this->assertEquals(2, $transformed['properties']['secondName']['propertyOrder']);
    }
}

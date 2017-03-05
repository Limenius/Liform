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
        $resolver->setTransformer('text', new StringTransformer());
        $transformer = new CompoundTransformer($resolver);
        $transformed = $transformer->transform($form);
        $this->assertTrue(is_array($transformed));
        $this->assertEquals('.{5,}', $transformed['properties']['firstName']['pattern']);
    }

    /**
     * testRequired
     *
     */
    public function testRequired()
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'firstName',
                TextType::class,
                ['required' => true]
            );
        $resolver = new Resolver();
        $resolver->setTransformer('text', new StringTransformer());
        $transformer = new CompoundTransformer($resolver);
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
        $resolver->setTransformer('text', new StringTransformer());
        $transformer = new CompoundTransformer($resolver);
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
        $resolver->setTransformer('text', new StringTransformer());
        $transformer = new CompoundTransformer($resolver);
        $transformed = $transformer->transform($form);

        $this->assertTrue(is_array($transformed));
        $this->assertArrayHasKey('title', $transformed['properties']['firstName']);
        $this->assertEquals('a label', $transformed['properties']['firstName']['title']);
    }
}

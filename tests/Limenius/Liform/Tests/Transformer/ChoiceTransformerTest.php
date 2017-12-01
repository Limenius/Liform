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

    /**
     * testMultipleChoice - Ensure that the schema produced matches what the ChoiceType would expect
     */
    public function testMultipleChoice()
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'favoriteColours',
                Type\ChoiceType::class,
                [
                    'multiple' => true,
                    'choices' => [
                        'RED' => 'Red',
                        'BLUE' => 'Blue',
                        'GREEN' => 'Green'
                    ]
                ]
            );


        $this->translator
            ->method('trans')
            ->will($this->returnCallback(function ($str) {
                return $str;
            }));

        $resolver = new Resolver();
        $resolver->setTransformer('choice', new Transformer\ChoiceTransformer($this->translator, null));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);
        $this->assertEquals(['RED', 'BLUE', 'GREEN'], $transformed['properties']['favoriteColours']['items']['enum_titles']);
        $this->assertEquals(['Red', 'Blue', 'Green'], $transformed['properties']['favoriteColours']['items']['enum']);
    }

    /**
     * testSingleEmptyChoice - Add a ChoiceType with no options, ensure that the schema does not have enum or enum_titles
     * as an enum must not be an empty array
     *
     * @see http://json-schema.org/latest/json-schema-validation.html#rfc.section.6.1.2
     */
    public function testSingleEmptyChoice()
    {
        $form = $this->factory->create(FormType::class)
            ->add(
                'favoriteColours',
                Type\ChoiceType::class,
                [
                    'choices' => []
                ]
            );

        $resolver = new Resolver();
        $resolver->setTransformer('choice', new Transformer\ChoiceTransformer($this->translator, null));
        $transformer = new CompoundTransformer($this->translator, null, $resolver);
        $transformed = $transformer->transform($form);

        $this->assertArrayNotHasKey('enum', $transformed['properties']['favoriteColours']);
        $this->assertArrayNotHasKey('enum_titles', $transformed['properties']['favoriteColours']);
        $this->assertEquals('string', $transformed['properties']['favoriteColours']['type']);
    }

    /**
     * testMultipleEmptyChoice - Add a ChoiceType with no options, ensure that the items do not have enum or enum_titles
     * as an enum must not be an empty array.
     *
     * @see http://json-schema.org/latest/json-schema-validation.html#rfc.section.6.1.2
     */
    public function testMultipleEmptyChoice()
    {
        $this->markTestIncomplete();
    }
}

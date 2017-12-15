<?php

/*
 * This file is part of the Limenius\Liform package.
 *
 * (c) Limenius <https://github.com/Limenius/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Limenius\Liform\Tests\Normalizer;

use Limenius\Liform\Serializer\Normalizer\InitialValuesNormalizer;
use Limenius\Liform\Tests\LiformTestCase;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * @author Nacho Martín <nacho@limenius.com>
 *
 * @see TypeTestCase
 */
class InitialValuesNormalizerTest extends LiformTestCase
{
    public function testConstruct()
    {
        $normalizer = new InitialValuesNormalizer();
        $this->assertInstanceOf(InitialValuesNormalizer::class, $normalizer);
    }

    public function testSimpleCase()
    {
        $form = $this->factory->create(FormType::class , ['firstName' => 'Joe'])
            ->add('firstName', TextType::class)
            ->add('secondName', TextType::class);
        $normalizer = new InitialValuesNormalizer();
        $data = (array) $normalizer->normalize($form);
        $this->assertEquals('Joe', $data['firstName']);
    }

    public function testChoiceExpandedMultiple()
    {
        $form = $this->factory->create(FormType::class, ['firstName' => ['A']])
            ->add(
                'firstName',
                ChoiceType::class,
                [
                    'choices' => ['a' => 'A', 'b' => 'B'],
                    'expanded' => true,
                    'multiple' => true,
                ]
            );

        $normalizer = new InitialValuesNormalizer();
        $data = (array) $normalizer->normalize($form);
        $this->assertEquals(['A'], $data['firstName']);

    }

    public function testChoiceExpanded()
    {
        $form = $this->factory->create(FormType::class, ['firstName' => 'A'])
            ->add(
                'firstName',
                ChoiceType::class,
                [
                    'choices' => ['a' => 'A', 'b' => 'B'],
                    'expanded' => true,
                ]
            );

        $normalizer = new InitialValuesNormalizer();
        $data = (array) $normalizer->normalize($form);
        $this->assertEquals('A', $data['firstName']);

    }
}

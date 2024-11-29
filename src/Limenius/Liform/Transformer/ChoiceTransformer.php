<?php

/*
 * This file is part of the Limenius\Liform package.
 *
 * (c) Limenius <https://github.com/Limenius/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Limenius\Liform\Transformer;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\ChoiceList\View\ChoiceGroupView;

/**
 * @author Nacho Martín <nacho@limenius.com>
 */
class ChoiceTransformer extends AbstractTransformer
{
    /**
     * {@inheritdoc}
     */
    public function transform(FormInterface $form, array $extensions = [], ?string $widget = null): array
    {
        $formView = $form->createView();

        $choices = [];
        $titles = [];
        foreach ($formView->vars['choices'] as $choiceView) {
            if ($choiceView instanceof ChoiceGroupView) {
                foreach ($choiceView->choices as $choiceItem) {
                    $choices[] = $choiceItem->value;
                    $titles[] = $this->translator->trans($choiceItem->label);
                }
            } else {
                $choices[] = $choiceView->value;
                $titles[] = $this->translator->trans($choiceView->label);
            }
        }

        if ($formView->vars['multiple']) {
            $schema = $this->transformMultiple($form, $choices, $titles);
        } else {
            $schema = $this->transformSingle($form, $choices, $titles);
        }

        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);

        return $schema;
    }

    /**
     * @param FormInterface $form
     * @param array         $choices
     * @param array         $titles
     *
     * @return array
     */
    private function transformSingle(FormInterface $form, $choices, $titles)
    {
        $formView = $form->createView();

        $schema = [
            'enum' => $choices,
            'enum_titles' => $titles, // For backwards compatibility
            'options' => [
                'enum_titles' => $titles,
            ],
            'type' => 'string',
        ];

        if ($formView->vars['expanded']) {
            $schema['widget'] = 'choice-expanded';
        }

        return $schema;
    }

    /**
     * @param FormInterface $form
     * @param array         $choices
     * @param array         $titles
     *
     * @return array
     */
    private function transformMultiple(FormInterface $form, $choices, $titles)
    {
        $formView = $form->createView();

        $schema = [
            'items' => [
                'type' => 'string',
                'enum' => $choices,
                'enum_titles' => $titles, // For backwards compatibility
                'options' => [
                    'enum_titles' => $titles,
                ],
            ],
            'minItems' => $this->isRequired($form) ? 1 : 0,
            'uniqueItems' => true,
            'type' => 'array',
        ];

        if ($formView->vars['expanded']) {
            $schema['widget'] = 'choice-multiple-expanded';
        }

        return $schema;
    }
}

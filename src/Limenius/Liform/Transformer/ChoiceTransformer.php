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
     * @inheritdoc
     */
    public function transform(FormInterface $form, array $extensions = [], $widget = null)
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
            $schema = [
                'items' => [
                    'type' => 'string',
                    'enum' => $choices,
                    'liform' => ['enum_titles' => $titles],
                    'minItems' => $this->isRequired($form) ? 1 : 0,
                ],
                'uniqueItems' => true,
                'type' => 'array',
            ];
        } else {
            $schema = [
                'enum' => $choices,
                'enum_titles' => $titles,
                'type' => 'string',
            ];
        }

        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);

        return $schema;
    }
}

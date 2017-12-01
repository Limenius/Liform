<?php

namespace Limenius\Liform\Transformer;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\ChoiceList\View\ChoiceGroupView;

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
                    'minItems' => $this->isRequired($form) ? 1 : 0,
                ],
                'uniqueItems' => true,
                'type' => 'object',
            ];

            if (count($choices)) {
                $schema['items']['enum'] = $choices;
                $schema['items']['enum_titles'] = $titles;
            }
        } else {
            $schema = [
                'type' => 'string',
            ];
            if (count($choices)) {
                $schema['enum'] = $choices;
                $schema['enum_titles'] = $titles;
            }
        }

        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);

        return $schema;
    }
}

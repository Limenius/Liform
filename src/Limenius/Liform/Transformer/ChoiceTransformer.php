<?php

namespace Limenius\Liform\Transformer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\ChoiceList\View\ChoiceGroupView;

class ChoiceTransformer extends AbstractTransformer
{
    public function transform(FormInterface $form, $extensions = [], $widget = null)
    {
        $formView = $form->createView();

        $choices = [];
        $titles = [];
        foreach ($formView->vars['choices'] as $choiceView) {
            if ($choiceView instanceof ChoiceGroupView) {
                foreach ($choiceView->choices as $choiceItem) {
                    $choices[] = $choiceItem->value;
                    $titles[] = $choiceItem->label;
                }
            } else {
                $choices[] = $choiceView->value;
                $titles[] = $choiceView->label;
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
                'type' => 'array'
            ];
        } else {
            $schema = [
                'enum' => $choices,
                'liform' => ['enum_titles' => $titles],
                'type' => 'string'
            ];
        }

        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);

        return $schema;
    }
}

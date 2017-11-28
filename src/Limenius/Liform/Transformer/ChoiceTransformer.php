<?php

namespace Limenius\Liform\Transformer;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\ChoiceList\View\ChoiceGroupView;

/**
 * Class: ChoiceTransformer
 *
 * @see AbstractTransformer
 */
class ChoiceTransformer extends AbstractTransformer
{
    /**
     * transform
     *
     * @param FormInterface $form
     * @param array         $extensions
     * @param srting|null   $widget
     *
     * @return array
     */
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
                    'enum_titles' => $titles,
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

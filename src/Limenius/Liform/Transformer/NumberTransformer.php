<?php

namespace Limenius\Liform\Transformer;

use Symfony\Component\Form\FormInterface;

/**
 * Class: NumberTransformer
 *
 * @see AbstractTransformer
 */
class NumberTransformer extends AbstractTransformer
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
        $schema = [
            'type' => 'number',
        ];
        if ($liform = $form->getConfig()->getOption('liform')) {
            if ($widget = $liform['widget']) {
                $schema['widget'] = $widget;
            }
        }
        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);

        return $schema;
    }
}

<?php

namespace Limenius\Liform\Transformer;
use Symfony\Component\Form\FormInterface;

class NumberTransformer extends AbstractTransformer
{
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

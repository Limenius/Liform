<?php

namespace Limenius\Liform\Transformer;
use Symfony\Component\Form\FormInterface;

class IntegerTransformer extends AbstractTransformer
{
    public function transform(FormInterface $form, $extensions = [], $widget = null)
    {
        $schema = [
            'type' => 'integer',
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

<?php

namespace Limenius\Liform\Transformer;
use Symfony\Component\Form\FormInterface;

class StringTransformer extends AbstractTransformer
{
    public function transform(FormInterface $form, $extensions = [], $widget = null)
    {
        $schema = [
            'type' => 'string',
        ];


        if ($liform = $form->getConfig()->getOption('liform')) {
            if (isset($liform['widget']) && $widget = $liform['widget']) {
                $schema['widget'] = $widget;
            }
        }

        $schema = $this->addCommonSpecs($form, $schema, $extensions, $widget);

        return $schema;
    }

}
